<?php
	class user_data
	{
		public $userid = 0;
		public $is_admin = 0;
		public $is_viewer = 0;
		public $is_interpreter = 0;
		public $email_verified = 0;
		public $language_ids = array();
		public $languages = array();
		public $timeslot_ids = array();
		public $timeslots = array();
		public $name;
		public $email;
		public $remarks;
		public $telephone;
		public $session_id;
		public $job_count;
		public $job_multiplier;
		public $gender;
	}

	class user extends user_data
	{
		function __construct($sqlcon,$sessionid = 0, $email = 0, $password = 0, $userid = 0) //continue, login, or simply load user profile
		{
			$this->sqlcon = $sqlcon;
			$email = $this->sqlcon->real_escape_string($email);
			$password = $this->sqlcon->real_escape_string($password);
			
			if($email AND $password) //login
			{
				$query = "SELECT * FROM users WHERE email_verified=1 AND email='$email' AND password = SHA2(CONCAT((SELECT salt FROM users WHERE email='$email'),'$password'),256)";
			}
			elseif($userid) //load user
			{
				$query = "SELECT * FROM users WHERE id='$userid'";
			}
			elseif($sessionid) //continue session
			{
				$query = "SELECT * FROM users WHERE session_id='".session_id()."'";
			}
			else
			{
				exit();
				//undefined behaviour!!
			}
			$result = $this->sqlcon->query($query);
			
			if($result->num_rows > 0) //get data
			{
				$row = $result->fetch_assoc();
				
				$this->userid = $row["id"];
				$this->is_admin = $row["admin"];
				$this->is_interpreter = $row["interpreter"];
				$this->email = $row['email'];
				$this->name = $row['name'];
				$this->remarks = $row['remarks'];
				$this->telephone = $row['telephone'];
				$this->is_viewer = $row['viewer'];
				$this->is_approved = $row['approved'];
				$this->email_verified = $row['email_verified'];
				$this->job_count = $row['job_count'];
				$this->job_multiplier = $row['job_multiplier'];
				$this->gender = $row['gender'];
				
				$query_languages = "SELECT * FROM interpreters i LEFT JOIN languages l ON i.language_id = l.id WHERE user_id='".$this->userid."'";
				$result_languages = $this->sqlcon->query($query_languages);
				$n = 0;
				while($row_lang = $result_languages->fetch_assoc())
				{
					$this->language_ids[$row_lang["language_id"]] = $row_lang["language_id"];
					$this->languages[$row_lang["language_id"]] = $row_lang["language"];
					$n++;
				}
				$query_timeslots = "SELECT * FROM user_availability a LEFT JOIN timeslots t ON a.timeslot_id = t.id WHERE a.user_id='".$this->userid."'";
				$result_timeslots = $this->sqlcon->query($query_timeslots);
				$n = 0;
				while($row_lang = $result_timeslots->fetch_assoc())
				{
					$this->timeslot_ids[$row_lang["timeslot_id"]] = $row_lang["timeslot_id"];
					$this->timeslots[$row_lang["timeslot_id"]] = $row_lang["name"];
					$n++;
				}
				
				if($sessionid OR ($email AND $password))
				{
					if($this->sqlcon->query("UPDATE users SET session_id = '".session_id()."', login_time='".time()."' WHERE id='".$this->userid."'"))
					{
						$this->session_id = $sessionid;
					}
				}
			}
		}
		
		public function change_password($new_password, $new_password_confirmed)
		{
			//has user rights to change data?
			if($this->change_rights())
			{
				//is the password matching the confirmation field
				if($new_password == $new_password_confirmed) 
				{
					$newSalt = generateRandomString(16);
					$query = "UPDATE users SET salt = '$newSalt', password = SHA2(CONCAT('$newSalt','$new_password'),256) WHERE id = '".$this->userid."'";
					if($this->sqlcon->query($query))
						return true;
				}
				return false;
			}
		}
		
		public function change_user_data($change_data)
		{
			$this->job_multiplier = $change_data->job_multiplier;
			if($change_data->name AND $change_data->name != $this->name)
				$this->name = $change_data->name;
			if($change_data->email AND $change_data->email != $this->email)
				$this->email = $change_data->email;
			if($change_data->remarks AND $change_data->remarks != $this->remarks)
				$this->remarks = $change_data->remarks;
			if($change_data->telephone AND $change_data->telephone != $this->telephone)
				$this->telephone = $change_data->telephone;
			if($change_data->gender AND $change_data->gender != $this->gender)
				$this->gender = $change_data->gender;
			
			//is active user admin?	
			if($this->change_admin_rights())
				$this->is_interpreter = $change_data->is_interpreter;

			//is active user admin?	
			if($this->change_admin_rights())
				$this->is_viewer = $change_data->is_viewer;

			//is active user admin?
			if($this->change_admin_rights())
			{
				$this->is_admin = $change_data->is_admin;
			}
			$this->email_verified = $change_data->email_verified;
			//has user rights to change data?
			if($this->change_rights())
			{
				$query = "UPDATE users SET name='".$this->name."', email='".$this->email."', remarks='".$this->remarks."', telephone='".$this->telephone."', admin='".$this->is_admin."', viewer='".$this->is_viewer."', interpreter='".$this->is_interpreter."', email_verified='".$this->email_verified."', job_multiplier='".$this->job_multiplier."', gender='".$this->gender."' WHERE id = '".$this->userid."'";
				$this->sqlcon->query($query);
			}
			
			$this->sqlcon->query("DELETE FROM interpreters WHERE user_id='".$this->userid."'");
			if(is_array($change_data->language_ids))
			{
				foreach($change_data->language_ids as $lang) 
				{
					$query = "INSERT INTO interpreters (user_id, language_id) VALUES ('".$this->userid."','$lang')";
					$this->sqlcon->query($query);
				}
			}
			$this->sqlcon->query("DELETE FROM user_availability WHERE user_id='".$this->userid."'");
			if(is_array($change_data->timeslot_ids))
			{
				foreach($change_data->timeslot_ids as $time) 
				{
					$query = "INSERT INTO user_availability (user_id, timeslot_id) VALUES ('".$this->userid."','$time')";
					$this->sqlcon->query("INSERT INTO user_availability (user_id, timeslot_id) VALUES ('".$this->userid."','$time')");
				}
			}
		}
		
		//right to change user data?
		public function change_rights()
		{
			//who is the active user
			$active_user = new user($this->sqlcon,session_id());
			
			//is the active user the targeted user or admin?
			if($this->session_id = $active_user->session_id OR $active_user->is_admin) 
			{
				return true;
			}
			else
				return false;
		}
		
		//right to change admin data?
		public function change_admin_rights()
		{
			$active_user = new user($this->sqlcon,session_id());
			if($active_user->is_admin)
				return true;
			else
				return false;
		}
		
		public function logout()
		{
			if($this->sqlcon->query("UPDATE users SET session_id = '' WHERE id='".$this->userid."'"))
			{
				$this->session_id = "";
				$this->is_admin = 0;
			}
		}
		
		public function delete()
		{
			$query = "DELETE FROM interpreters WHERE user_id='".$this->userid."'";
			$this->sqlcon->query($query);
			$query = "DELETE FROM user_availability WHERE user_id='".$this->userid."'";
			$this->sqlcon->query($query);
			$query = "DELETE FROM users WHERE id='".$this->userid."'";
			$this->sqlcon->query($query);
		}
	}

	//a list of users depending on selected criteria
	class user_list
	{
		function __construct($sqlcon, $require_admin, $require_interpreter, $require_languages, $require_timeslots, $order) //continue, login, or simply load user profile
		{
			$this->sqlcon = $sqlcon;
			$this->cur_pos = 0;
			$query = "SELECT DISTINCT id FROM users";
			if($require_admin OR $require_interpreter OR is_array($require_languages) OR is_array($require_timeslots))
			{
				$more = false;
				$query .= " WHERE";
				if($require_admin)
				{
					$query .= " admin=1";
					$more = true;
				}
				if($require_interpreter==1)
				{
					if($more)
						$query .= " AND interpreter=1";
					else
					{
						$query .= " interpreter=1";
						$more = true;
					}
				}
				if($require_interpreter==-1)
				{
					if($more)
						$query .= " AND interpreter=0";
					else
					{
						$query .= " interpreter=0";
						$more = true;
					}
				}
			}
			if($order)
				$query .= " ORDER BY $order ASC";
			
			$result = $this->sqlcon->query($query);
			$this->num_users = 0;
			while($row = $result->fetch_assoc())
			{
				$pass = true;
				if(is_array($require_languages))
				{
					$ids = join(",",$require_languages);
					$query = "SELECT language_id FROM interpreters WHERE user_id=".$row['id']." AND language_id IN ($ids)";

					if($this->sqlcon->query($query)->num_rows != count($require_languages)) $pass = false;
				}
				if(is_array($require_timeslots))
				{
					$ids = join(",",$require_timeslots);
					$query = "SELECT timeslot_id FROM user_availability WHERE user_id=".$row['id']." AND timeslot_id IN ($ids)";

					if($this->sqlcon->query($query)->num_rows != count($require_timeslots)) $pass = false;
				}
				if($pass)
				{
					$this->user[$this->num_users] = new user($this->sqlcon,0,0,0,$row['id']);
					$this->num_users++;
				}
			}
		}
		
		public function get_user()
		{
			if($this->num_users == $this->cur_pos)
				return false;
			else
			{
				$return = new user_data();
				$return->userid = $this->user[$this->cur_pos]->userid;
				$return->name = $this->user[$this->cur_pos]->name;
				$return->email = $this->user[$this->cur_pos]->email;
				$return->telephone = $this->user[$this->cur_pos]->telephone;
				$return->gender = $this->user[$this->cur_pos]->gender;
				$return->remarks = $this->user[$this->cur_pos]->remarks;
				$return->is_admin = $this->user[$this->cur_pos]->is_admin;
				$return->is_interpreter = $this->user[$this->cur_pos]->is_interpreter;
				$return->is_viewer = $this->user[$this->cur_pos]->is_viewer;
				$return->languages = $this->user[$this->cur_pos]->languages;
				$return->timeslots = $this->user[$this->cur_pos]->timeslots;
				$return->job_count = $this->user[$this->cur_pos]->job_count;
				$return->email_verified = $this->user[$this->cur_pos]->email_verified;
				$this->cur_pos++;
				return $return;
			}
		}
	}
?>
