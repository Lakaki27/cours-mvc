document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".deleteBtn").forEach((deleteBtn) => {
        deleteBtn.addEventListener("click", async function (e) {
            e.preventDefault()
            let id = e.target.id.split("_")[1]

            if (await confirm("Supprimer ce personnage ? (Action irréversible !)")) {
                await fetch(`/personnage/${id}/delete`)
                window.location.reload()
            }
        })
    })
})