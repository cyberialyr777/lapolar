document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");

  searchInput.addEventListener("input", function () {
    const searchTerm = searchInput.value.trim().toLowerCase();
    filterCobros(searchTerm);
  });

  function filterCobros(searchTerm) {
    const cobros = document.querySelectorAll(".cobross");

    cobros.forEach((cobro) => {
      const servicio = cobro.querySelector(".refatexto").textContent.toLowerCase();
      const numeroFactura = cobro.querySelector(".numFactura").textContent.toLowerCase();
    
      if (
        servicio.includes(searchTerm) ||
        numeroFactura.includes(searchTerm) 
        
      ) {
        cobro.style.display = "block";
      } else {
        cobro.style.display = "none";
      }
    });
  }
});
