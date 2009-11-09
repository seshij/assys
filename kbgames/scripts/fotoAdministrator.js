$.extend({
    getUrlVars: function(){
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    getUrlVar: function(name){
        return $.getUrlVars()[name];
    }
});

$().ready(function() {

    if($.getUrlVar('option')=='juegos'){
        $('#caratula_adelante').attr('readonly', true);
        $('#caratula_atras').attr('readonly', true);
        
        for(var i=0;i<5;i++){
            $('#ruta_f_'+i).attr('readonly', true);
        }

        $('#delete_button0').click(function(){
            $.post("uploadFoto.php", {
                accion: "delete",
                id_foto:$("#id_foto_0").val(),
                nom_foto:$("#ruta_f_0").val(),
                cod:$("#id_juego").val()
            }, function(data){
                $("#img0").remove("img"),
                $("#ruta_f_0").val('');
            });
        });
        $('#delete_button1').click(function(){
            $.post("uploadFoto.php", {
                accion: "delete",
                id_foto:$("#id_foto_1").val(),
                nom_foto:$("#ruta_f_1").val(),
                cod:$("#id_juego").val()
            }, function(data){
                $("#img1").remove("img"),
                $("#ruta_f_1").val('');
            });
        });
        $('#delete_button2').click(function(){
            $.post("uploadFoto.php", {
                accion: "delete",
                id_foto:$("#id_foto_2").val(),
                nom_foto:$("#ruta_f_2").val(),
                cod:$("#id_juego").val()
            }, function(data){
                $("#img2").remove("img"),
                $("#ruta_f_2").val('');
            });
        });
        $('#delete_button3').click(function(){
            $.post("uploadFoto.php", {
                accion: "delete",
                id_foto:$("#id_foto_3").val(),
                nom_foto:$("#ruta_f_3").val(),
                cod:$("#id_juego").val()
            }, function(data){
                $("#img3").remove("img"),
                $("#ruta_f_3").val('');
            });
        });
        $('#delete_button4').click(function(){
            $.post("uploadFoto.php", {
                accion: "delete",
                id_foto:$("#id_foto_4").val(),
                nom_foto:$("#ruta_f_4").val(),
                cod:$("#id_juego").val()
            }, function(data){
                $("#img4").remove("img"),
                $("#ruta_f_4").val('');
            });
        });
        $('#delete_caratula_adelante').click(function(){
            $.post("uploadFoto.php", {
                accion: "delete_caratula",
                nom_foto:$("#imagen_adelante").val(),
                cod:$("#id_juego").val()
            }, function(data){
                $("#imgadelante").remove("img"),
                $("#imagen_adelante").val('');
            });
        });
        $('#delete_caratula_atras').click(function(){
            $.post("uploadFoto.php", {
                accion: "delete_caratula",
                nom_foto:$("#imagen_atras").val(),
                cod:$("#id_juego").val()
            }, function(data){
                $("#imgatras").remove("img"),
                $("#imagen_atras").val('');
            });
        });

        var upload0=new AjaxUpload('#upload_button0', {
            action: 'uploadFoto.php',
            name: 'upload',
            data: {},
            autoSubmit: true,
            responseType: false,

            onChange: function(file, extension){},

            onSubmit: function(file , ext){
                if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                    alert('Error: invalid file extension');
                    return false;
                }
                upload0.setData({
                    cod: $('#id_juego').val(),
                    accion: "upload"
                });
                if($('#id_juego').val()==""){
                    alert('Debe llenar el campo id_juego');
                    return false;
                }
                return true;
            },

            onComplete: function(file, response) {
                $('#ruta_f_0').val(file);
                $("#img_0").append($(document.createElement("img")).attr({
                    src: "../media/images/thumbs/"+$('#id_juego').val()+"/"+file,
                    id:"img0"
                })).show();
            }
        });

        var upload1=new AjaxUpload('#upload_button1', {
            action: 'uploadFoto.php',
            name: 'upload',
            data: {},
            autoSubmit: true,
            responseType: false,

            onChange: function(file, extension){},

            onSubmit: function(file , ext){
                if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                    alert('Error: invalid file extension');
                    return false;
                }
                upload1.setData({
                    cod: $('#id_juego').val(),
                    accion: "upload"
                });
                if($('#id_juego').val()==""){
                    alert('Debe llenar el campo id_juego');
                    return false;
                }
                return true;
            },

            onComplete: function(file, response) {
                $('#ruta_f_1').val(file);
                $("#img_1").append($(document.createElement("img")).attr({
                    src: "../media/images/thumbs/"+$('#id_juego').val()+"/"+file,
                    id:"img1"
                })).show();
            }
        });

        var upload2=new AjaxUpload('#upload_button2', {
            action: 'uploadFoto.php',
            name: 'upload',
            data: {},
            autoSubmit: true,
            responseType: false,

            onChange: function(file, extension){},

            onSubmit: function(file , ext){
                if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                    alert('Error: invalid file extension');
                    return false;
                }
                upload2.setData({
                    cod: $('#id_juego').val(),
                    accion: "upload"
                });
                if($('#id_juego').val()==""){
                    alert('Debe llenar el campo id_juego');
                    return false;
                }
                return true;
            },

            onComplete: function(file, response) {
                $('#ruta_f_2').val(file);
                $("#img_2").append($(document.createElement("img")).attr({
                    src: "../media/images/thumbs/"+$('#id_juego').val()+"/"+file,
                    id:"img2"
                })).show();
            }
        });

        var upload3=new AjaxUpload('#upload_button3', {
            action: 'uploadFoto.php',
            name: 'upload',
            data: {},
            autoSubmit: true,
            responseType: false,

            onChange: function(file, extension){},

            onSubmit: function(file , ext){
                if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                    alert('Error: invalid file extension');
                    return false;
                }
                upload3.setData({
                    cod: $('#id_juego').val(),
                    accion: "upload"
                });
                if($('#id_juego').val()==""){
                    alert('Debe llenar el campo id_juego');
                    return false;
                }
                return true;
            },

            onComplete: function(file, response) {
                $('#ruta_f_3').val(file);
                $("#img_3").append($(document.createElement("img")).attr({
                    src: "../media/images/thumbs/"+$('#id_juego').val()+"/"+file,
                    id:"img3"
                })).show();
            }
        });
        
        var upload4=new AjaxUpload('#upload_button4', {
            action: 'uploadFoto.php',
            name: 'upload',
            data: {},
            autoSubmit: true,
            responseType: false,

            onChange: function(file, extension){},

            onSubmit: function(file , ext){
                if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                    alert('Error: invalid file extension');
                    return false;
                }
                upload4.setData({
                    cod: $('#id_juego').val(),
                    accion: "upload"
                });
                if($('#id_juego').val()==""){
                    alert('Debe llenar el campo id_juego');
                    return false;
                }
                return true;
            },

            onComplete: function(file, response) {
                $('#ruta_f_4').val(file);
                $("#img_4").append($(document.createElement("img")).attr({
                    src: "../media/images/thumbs/"+$('#id_juego').val()+"/"+file,
                    id:"img4"
                })).show();
            }
        });

        var upload_caratula_adelante=new AjaxUpload('#upload_caratula_adelante', {
            action: 'uploadFoto.php',
            name: 'upload_caratula',
            data: {},
            autoSubmit: true,
            responseType: false,

            onChange: function(file, extension){},

            onSubmit: function(file , ext){
                if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                    alert('Error: invalid file extension');
                    return false;
                }
                upload_caratula_adelante.setData({
                    cod: $('#id_juego').val(),
                    accion: "upload_caratula"
                });
                if($('#id_juego').val()==""){
                    alert('Debe llenar el campo id_juego');
                    return false;
                }
                return true;
            },

            onComplete: function(file, response) {
                $('#imagen_adelante').val(file);
                $("#img_adelante").append($(document.createElement("img")).attr({
                    src: "../media/caratulas/thumbs/"+$('#id_juego').val()+"/"+file,
                    id:"imgadelante"
                })).show();
            }
        });

        var upload_caratula_atras=new AjaxUpload('#upload_caratula_atras', {
            action: 'uploadFoto.php',
            name: 'upload_caratula',
            data: {},
            autoSubmit: true,
            responseType: false,

            onChange: function(file, extension){},

            onSubmit: function(file , ext){
                if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                    alert('Error: invalid file extension');
                    return false;
                }
                upload_caratula_atras.setData({
                    cod: $('#id_juego').val(),
                    accion: "upload_caratula"
                });
                if($('#id_juego').val()==""){
                    alert('Debe llenar el campo id_juego');
                    return false;
                }
                return true;
            },

            onComplete: function(file, response) {
                $('#imagen_atras').val(file);
                $("#img_atras").append($(document.createElement("img")).attr({
                    src: "../media/caratulas/thumbs/"+$('#id_juego').val()+"/"+file,
                    id:"imgatras"
                })).show();
            }
        });
    }else if($.getUrlVar('option')=='noticias'){
        $('#imagen').attr('readonly', true);

        $('#delete_imagen_noticia').click(function(){
            $.post("uploadFoto.php", {
                accion: "delete_imgnoticia",
                nom_foto:$("#imagen").val()
            }, function(data){
                $("#imgnoticia").remove("img"),
                $("#imagen").val('');
            });
        });
        
        var upload_imagen_noticia=new AjaxUpload('#upload_imagen_noticia', {
            action: 'uploadFoto.php',
            name: 'upload_imgnoticia',
            data: {},
            autoSubmit: true,
            responseType: false,

            onChange: function(file, extension){},

            onSubmit: function(file , ext){
                if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                    alert('Error: invalid file extension');
                    return false;
                }
                upload_imagen_noticia.setData({
                    cod: "nojda",
                    accion: "upload_imgnoticia"
                });
                return true;
            },

            onComplete: function(file, response) {
                $('#imagen').val(file);
                $("#img_noticia").append($(document.createElement("img")).attr({
                    src: "../media/noticias/thumbs/"+file,
                    id:"imgnoticia"
                })).show();
            }
        });
    }
});
