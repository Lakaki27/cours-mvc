document.getElementById("triggerBagarre").addEventListener("click", function() {
    let personnageA = document.getElementById("personnageASelect").value
    let personnageB = document.getElementById("personnageBSelect").value

    if (personnageA === "" && personnageB === "") {
        alert("Merci de sélectionner deux personnages pour une bagarre.")
        return;
    } else if ((personnageA === "" && personnageB !== "") || (personnageA !== "" && personnageB === "")) {
        alert("Il faut deux personnages valides pour un combat.")
        return;
    } else if ((personnageA === personnageB) && (personnageA !== "") && (personnageB !== "")) {
        alert("Attention à la schizophrénie, impossible de se battre contre soi-même.")
        return;
    } else {
        window.location.href = `/bagarre?a=${personnageA}&b=${personnageB}`
    }
})