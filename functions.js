function edit_user(uid,target)
{   
	jQuery.ajax({
	  url: "ajax.php?linkid=9",
	  method: "POST",
	  data: { id: uid},
	  cache: false
	})
  .done(function( html ) {
    $( target ).html( html );
    location.hash = "#" + uid;
  });
}


function del_user(uid,target,message)
{   
	var r = confirm(message);
	if(r)
	{
		jQuery.ajax({
			url: "ajax.php?linkid=10",
			method: "POST",
			data: { id: uid},
			cache: false
		})
			.done(function( html ) {
			$( target ).html( html );
		});
	}
}
