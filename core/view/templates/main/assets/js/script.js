const form = document.getElementById("formUserTransaction");

if (form) {
  form.addEventListener("submit", function (event) {
    event.preventDefault();
    const target = event.target;
    const formData = new FormData(target);
    const queryString = new URLSearchParams(formData).toString();
    const action = (target.getAttribute("action") || "/") + "?";

    fetch(action + queryString, {
      method: target.method || "GET",
      headers: { "X-Requested-With": "XMLHttpRequest" },
    })
      .then((response) => response.text())
      .then((html) => {
        const existingData = document.getElementById("data");
        if (existingData) {
          existingData.innerHTML = html;
        } else {
          dataBlock = document.createElement("div");
          dataBlock.id = "data";
          dataBlock.innerHTML = html;
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });
}
