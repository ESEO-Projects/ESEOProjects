$(".enableUser").on('click', event => {
  const target = $(event.target);
  var id = target.data('id');
  if(id != undefined){
      fetch("user/"+id+"/enable")
        .then((res) => res.json())
        .then(function(res){
          if(res.success != true){
              throw Error('Une erreur est survenue');
          }
          else{
            console.log(target);
            target.parent().fadeOut('slow');
            console.log(target.parent().parent());
          }
        })
        .then(console.log("OK"))
        .catch(console.log("NOT OK"))
  }
});
