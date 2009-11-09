function goCliente(){
    $("#addContact").click(function(event){
        event.preventDefault();
        if($("#div_contactos table").length>=2){
            $(this).hide();
        }
        var aleat = Math.random() * 5000;
        var n = Math.round(aleat);
        $("#div_contactos").append("<table id='contact_"+n+"' class='innertable'><tr><th colspan='2'>Datos Contacto</th></tr><tr><td>Nombre</td><td><input type='hidden' id='id_c_"+n+"' name='id_c[]' value='-1'><input type='text' id='n_c_"+n+"' name='n_c[]'></td></tr><tr><td>Tel&eacute;fono</td><td><input type='text' id='tel_c_"+n+"' name='tel_c[]'> </td></tr><tr><td>Celular</td><td><input type='text' id='cel_c_"+n+"' name='cel_c[]'></td></tr><tr><td>Email</td><td><input type='text' id='mail_c_"+n+"' name='mail_c[]'></td></tr><tr><td colspan='2'><a onclick='confirma("+n+")'>Borrar</a></td></tr></table>");
    }
    )
}

function confirma(n){
    //alert("NN:" + n);
    $("#dialogo").html('<p>Desea borrar el contacto?</p>').dialog(
    {
        modal: true
        , title: "Confirmaci&oacute;n"
        , overlay: {
              opacity : 0.4
            , background : "#000"
        }
        , buttons: {
            "OK": function() {
                $("#contact_"+n).remove();
                var tabs = $("#div_contactos table").length;
                if (tabs<3){
                    $("#addContact").show();
                }
                $(this).dialog("close");
                $(this).dialog("destroy");

            }
            , "Cancel": function() {
                $(this).dialog("close");
                $(this).dialog("destroy");
            }
        }
    });
 
}

function goFactura(){
   // $("#cliente").change(setRazon);
    $("#valorproducto").keyup(sacarTotal);
    $("#reteproducto").keyup(sacarTotal);
    $("#valorservicio").keyup(sacarTotal);
    $("#reteservicio").keyup(sacarTotal);
    $("#iva").keyup(sacarTotal);
    $("#reteiva").keyup(sacarTotal);
    $("#reteica").keyup(sacarTotal);

    sacarTotal();

}
/*
function setRazon(event){
    //alert ("alooo");
    $("#cliente option:selected").each(function () {
                $("#razon").val($(this).text());
              });
}*/
function sacarTotal(event){
    var vp=$("#valorproducto").val();
    var rp=$("#reteproducto").val();
    var vs=$("#valorservicio").val();
    var rs=$("#reteservicio").val();
    var iva=$("#iva").val();
    var riva=$("#reteiva").val();
    var rica=$("#reteica").val();

    if(vp==""){
        vp=0;
       // $("#valorproducto").val(0);
    }
    if(rp==""){
        rp=0;
       // $("#reteproducto").val(0);
    }
    if(vs==""){
        vs=0;
      //  $("#valorservicio").val(0);
    }
    if(rs==""){
        rs=0;
       // $("#reteservicio").val(0);
    }
    if(iva==""){
        iva=0;
       // $("#iva").val(0);
    }
    if(riva==""){
        riva=0;
       // $("#reteiva").val(0);
    }
    if(rica==""){
        rica=0;
       // $("#reteica").val(0);
    }


    if(isNaN(vp) || isNaN(rp) ||isNaN(vs) ||isNaN(rs) ||isNaN(iva) ||isNaN(riva) ||isNaN(rica)){
        //alert("NN:" + n);
        $("#dialogo").html('<p>Revise que los campos sean num&eacute;ricos y que el separador decimal sea punto (.)</p>').dialog(
        {
            modal: false
            , title: "Atenci&oacute;n"
            , buttons: {
                "OK": function() {
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                }
            }
        });
    }else{
        var vpf=parseFloat(vp);
        var rpf=parseFloat(rp);
        var vsf=parseFloat(vs);
        var rsf=parseFloat(rs);
        var ivaf=parseFloat(iva);
        var rivaf=parseFloat(riva);
        var ricaf=parseFloat(rica);

        var vb=vpf+vsf;
        var vpt=vpf-(vpf*(rpf/100));
        var vst=vsf-(vsf*(rsf/100));
        var vbt=vpt+vst;
        var drica=(vb*(rica/100));
        var driva=(vb*(riva/100));
        var diva=(vb*(iva/100));
        var total=vbt-drica+diva-driva;
        $("#valorbase").val(vb);
        $("#total").val(total);
    }

}