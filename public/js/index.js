const fechaLanzamiento = new Date("2026-06-20T19:00:00").getTime();

const temporizador = setInterval(() => {

    const fechaActual = new Date().getTime();
    const tiempoRestante = fechaLanzamiento - fechaActual;
    const dias = Math.floor(tiempoRestante / (1000 * 60 * 60 * 24));
    const horas = Math.floor((tiempoRestante % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutos = Math.floor((tiempoRestante % (1000 * 60 * 60)) / (1000 * 60));
    const segundos = Math.floor((tiempoRestante % (1000 * 60)) / 1000);

    document.getElementById("dias").innerHTML = dias;
    document.getElementById("horas").innerHTML = horas;
    document.getElementById("minutos").innerHTML = minutos;
    document.getElementById("segundos").innerHTML = segundos;



}, 1000);

const formularioAcceso = document.getElementById("formSolicitarAcceso");

formularioAcceso.addEventListener('submit', function (e) {

    e.preventDefault();
    const correo = document.getElementById("emailAcceso").value;

    console.log("Correo Acesso anticipado:  " + correo);



    const modal = bootstrap.Modal.getInstance(document.getElementById('modalSolicitarAcceso'));
    modal.hide();

});