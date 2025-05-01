function inativarEvento(evento_id) {
  if (confirm("Tem certeza que deseja inativar este evento?")) {
      fetch("inativar_evento.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: new URLSearchParams({ evento_id: evento_id }),
          credentials: "include"
      })
      .then(response => response.json())
      .then(data => {
          alert(data.message);
          if (data.status === "success") {
              location.reload();
          }
      })
      .catch(error => console.error("Erro:", error));
  }
}
