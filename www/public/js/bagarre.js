document.getElementById("triggerNextTurn").addEventListener("click", async function (e) {
    e.preventDefault()
    let moveA = document.getElementById("moveASelect").value
    let moveB = document.getElementById("moveBSelect").value

    let response = await fetch(`/bagarre/move${window.location.search}&move1=${moveA}&move2=${moveB}`)
    let resp = await response.json();

    await handleCombat(resp)
})

function handleCombat(turnData) {
    document.getElementById("battleTextContainer").textContent = turnData.content
    document.getElementById("labelPVa").textContent = turnData.PVs.PVa.toString()
    document.getElementById("labelPVb").textContent = turnData.PVs.PVb.toString()
    document.getElementById("labelForceA").textContent = turnData.forces.forceA.toString()
    document.getElementById("labelForceB").textContent = turnData.forces.forceB.toString()

    if (turnData.isFinished) {
        document.getElementById("moveASelect").disabled = true
        document.getElementById("moveBSelect").disabled = true
        document.getElementById("triggerNextTurn").remove()

        if (turnData.PVs.PVa <= 0) {
            document.getElementById("cardA").style.filter = "grayscale(1)"
        } else if (turnData.PVs.PVb <= 0) {
            document.getElementById("cardB").style.filter = "grayscale(1)"
        }

        document.getElementById("battleTextContainer").textContent = document.getElementById("battleTextContainer").textContent + "\n\nRetour à la page d'accueil dans 10 secondes..."

        setTimeout(function () {
            window.location.href = "/personnage"
        }, 10000)
    }
}