const btnAbrirModal = document.querySelector("#btn_abrir_modal"); //toma el boton por la id para darle una funcion cuando le den click
const btnCerrarModal = document.querySelector("#btn_cerrar_modal");//toma el boton por la id para darle una funcion cuando le den click
const Modal = document.querySelector("#modal");

btnAbrirModal.addEventListener("click",()=>{ //funcion para mostrar el dialog
    Modal.showModal();
})

btnCerrarModal.addEventListener("click",()=>{    //funcion para cerrar el dialogs
    Modal.close();
})
