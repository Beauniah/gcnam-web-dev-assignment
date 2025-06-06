document.getElementById("rateForm").addEventListener("submit", async function (e) {
  e.preventDefault();

  const unitName = document.getElementById("unitName").value;
  const arrival = document.getElementById("arrival").value;
  const departure = document.getElementById("departure").value;
  const occupants = parseInt(document.getElementById("occupants").value);
  const ages = document.getElementById("ages").value.split(",").map(age => parseInt(age.trim()));

  const payload = {
    "Unit Name": unitName,
    "Arrival": arrival,
    "Departure": departure,
    "Occupants": occupants,
    "Ages": ages
  };

  try {
    const response = await fetch("https://scaling-barnacle-6wgg4gp4wrv245r5-8000.app.github.dev/api.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(payload)
    });

    const result = await response.json();
    document.getElementById("result").textContent = JSON.stringify(result, null, 2);
  } catch (error) {
    document.getElementById("result").textContent = "Error: " + error.message;
  }
});
