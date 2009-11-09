$.validator.addMethod("noBlank",
    function(value, element) {
        return this.optional(element) || value != " ";
    }, " * Campo requerido");

$.validator.addMethod(
    "fecha",
    function(value, element) {
        // put your own logic here, this is just a (crappy) example
        return value.match(/^\d\d\d\d?\-\d\d?\-\d\d$/);
    },
    "Favor escribir la fecha con el formato (aaaa-mm-dd)"
);

$().submit(function(){
    $("#id_juego").removeAttr('disabled');
    $("#id_miembro").removeAttr('disabled');
});

$().ready(function() {
    // validate signup form on keyup and submit
    $("#juego_form").validate({
        onkeyup:false,
        rules: {
            id_juego: {
                required:true,
                remote:{
                    url:"adminHelper.php?accion=codigo",
                    type:"post",
                    data:{
                        idjuego:function() {
                            return $("#id_juego").val();
                        }
                    }
                }
            },
            id_rating: "noBlank",
            titulo: "required",
            descripcion: "required",
            desarrollador: "required",
            stock:{
                required:true,
                number:true
            },
            fecha_lanzamiento:{
                required:true,
                fecha:true
            },
            precio:{
                required:true,
                number:true
            },
            estado:"required"
        },
        messages: {
            id_juego: {
                required:" * campo requerido",
                remote:" * El codigo ya se encuentra en la base de datos"
            },
            id_rating: " * campo requerido",
            titulo: " * campo requerido",
            descripcion: " * campo requerido",
            desarrollador: " * campo requerido",
            stock: {
                required:" * campo requerido",
                number:" * solo valor numerico"
            },
            fecha_lanzamiento: {
                required:" * campo requerido",
                fecha:" Favor escribir la fecha con el formato (aaaa-mm-dd)"
            },
            precio: {
                required:" * campo requerido",
                number:" * solo valor numerico"
            },
            estado: " * campo requerido"
        }
    });

    $("#miembro_form").validate({
        onkeyup:false,
        rules: {
            id_miembro: {
                required:true,
                remote:{
                    url:"adminHelper.php?accion=miembro",
                    type:"post",
                    data:{
                        idmiembro:function() {
                            return $("#id_miembro").val();
                        }
                    }
                }
            },
            nombres: "required",
            apellidos: "required",
            tipo_id: "noBlank",
            email: "email",
            direccion: "required",
            telefono: {
                required:true,
                number:true
            },
            ciudad: "required",
            fecha_nacimiento: {
                required:true,
                fecha:true
            },
            sexo: "required"
        },
        messages: {
            id_miembro: {
                required:" * campo requerido",
                remote:" * El codigo ya se encuentra en la base de datos"
            },
            nombres: " * campo requerido",
            apellidos: " * campo requerido",
            tipo_id: " * campo requerido",
            email: " * Ingrese una dirección valida",
            direccion: " * campo requerido",
            telefono: {
                required:" * campo requerido",
                number:" * solo valor numerico"
            },
            fecha_nacimiento: {
                required:" * campo requerido",
                fecha:" Favor escribir la fecha con el formato (aaaa-mm-dd)"
            },
            sexo: " * campo requerido"
        }
    });
});