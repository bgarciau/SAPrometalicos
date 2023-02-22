const btnAbrirModal = document.querySelector("#btn_abrir_modal");
const btnCerrarModal = document.querySelector("#btn_cerrar_modal");
const Modal = document.querySelector("#modal");

btnAbrirModal.addEventListener("click",()=>{
    Modal.showModal();
})

btnCerrarModal.addEventListener("click",()=>{    
    Modal.close();
})

