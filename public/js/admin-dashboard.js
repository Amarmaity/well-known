document.addEventListener("DOMContentLoaded", function () {
    const toast = document.getElementById("dashboardToast");
    const toastMessage = document.getElementById("dashboardToastMessage");

    /**
     * Display dashboard toast message.
     *
     * @param {string} message
     */
    function showDashboardToast(message) {
        if (!toast || !toastMessage) {
            return;
        }

        toastMessage.textContent = message;
        toast.classList.add("show");

        window.clearTimeout(window.dashboardToastTimer);

        window.dashboardToastTimer = window.setTimeout(function () {
            toast.classList.remove("show");
        }, 2500);
    }

    /**
     * Filter HTML table rows.
     *
     * @param {HTMLInputElement|null} searchInput
     * @param {HTMLTableElement|null} table
     */
    function initializeTableSearch(searchInput, table) {
        if (!searchInput || !table) {
            return;
        }

        searchInput.addEventListener("input", function () {
            const searchValue = this.value.trim().toLowerCase();
            const tableRows = table.querySelectorAll("tbody tr");

            tableRows.forEach(function (row) {
                const rowText = row.textContent.toLowerCase();

                row.style.display = rowText.includes(searchValue)
                    ? ""
                    : "none";
            });
        });
    }

    /**
     * Global dashboard search.
     */
    const globalSearchInput = document.getElementById(
        "globalDashboardSearch"
    );

    if (globalSearchInput) {
        globalSearchInput.addEventListener("input", function () {
            const searchValue = this.value.trim().toLowerCase();
            const dashboardCards = document.querySelectorAll(
                ".dashboard-card, .summary-card"
            );

            dashboardCards.forEach(function (card) {
                const cardText = card.textContent.toLowerCase();

                card.style.display =
                    searchValue === "" || cardText.includes(searchValue)
                        ? ""
                        : "none";
            });
        });
    }

    /**
     * Leave balance employee search.
     */
    initializeTableSearch(
        document.getElementById("leaveEmployeeSearch"),
        document.getElementById("leaveBalanceTable")
    );

    /**
     * Appraisal employee search.
     */
    initializeTableSearch(
        document.getElementById("appraisalEmployeeSearch"),
        document.getElementById("appraisalEmployeeTable")
    );

    /**
     * Attendance record search.
     */
    initializeTableSearch(
        document.getElementById("attendanceRecordSearch"),
        document.getElementById("attendanceRecordTable")
    );

    /**
     * Excel import button.
     */
    const importExcelButton = document.getElementById(
        "importExcelButton"
    );

    const excelFileInput = document.getElementById("excelFileInput");

    if (importExcelButton && excelFileInput) {
        importExcelButton.addEventListener("click", function () {
            excelFileInput.click();
        });

        excelFileInput.addEventListener("change", function () {
            const selectedFile = this.files[0];

            if (!selectedFile) {
                return;
            }

            const validExtensions = ["xlsx", "xls", "csv"];
            const extension = selectedFile.name
                .split(".")
                .pop()
                .toLowerCase();

            if (!validExtensions.includes(extension)) {
                showDashboardToast(
                    "Please select an Excel or CSV file."
                );

                this.value = "";
                return;
            }

            showDashboardToast(
                selectedFile.name + " selected successfully."
            );

            addUploadHistory(selectedFile.name);

            this.value = "";
        });
    }

    /**
     * Add a static upload history row after selecting a file.
     *
     * @param {string} fileName
     */
    function addUploadHistory(fileName) {
        const uploadHistoryList = document.getElementById(
            "uploadHistoryList"
        );

        if (!uploadHistoryList) {
            return;
        }

        const currentDate = new Date();

        const formattedDate = currentDate.toLocaleString("en-IN", {
            day: "2-digit",
            month: "short",
            hour: "2-digit",
            minute: "2-digit",
        });

        const fileNameWithoutExtension = fileName.replace(
            /\.[^/.]+$/,
            ""
        );

        const uploadItem = document.createElement("div");
        uploadItem.className = "upload-history-item";

        uploadItem.innerHTML = `
            <div>
                <h5>${escapeHtml(fileNameWithoutExtension)}</h5>
                <p>Date: ${escapeHtml(formattedDate)}</p>
                <p>Records: Processing</p>
            </div>

            <div class="upload-user">
                <i class="bi bi-download"></i>
                <span>By Admin</span>
                <small>File Selected</small>
            </div>
        `;

        uploadHistoryList.prepend(uploadItem);
    }

    /**
     * Prevent HTML injection.
     *
     * @param {string} value
     * @returns {string}
     */
    function escapeHtml(value) {
        const div = document.createElement("div");
        div.textContent = value;

        return div.innerHTML;
    }

    /**
     * Apply leave button.
     */
    const applyLeaveButton = document.getElementById(
        "applyLeaveButton"
    );

    if (applyLeaveButton) {
        applyLeaveButton.addEventListener("click", function () {
            showDashboardToast("Leave application form opened.");
        });
    }

    /**
     * View details buttons.
     */
    const detailsButtons = document.querySelectorAll(
        ".view-details-button"
    );

    detailsButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            const tableRow = this.closest("tr");
            const employeeName =
                tableRow?.querySelector(".employee-cell strong")
                    ?.textContent || "Employee";

            showDashboardToast(
                employeeName + " details opened."
            );
        });
    });

    /**
     * Generic buttons containing data-message.
     */
    const messageButtons = document.querySelectorAll("[data-message]");

    messageButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            const message =
                this.getAttribute("data-message") ||
                "Action completed.";

            showDashboardToast(message);
        });
    });

    /**
     * Static pagination active state.
     */
    const paginationContainers = document.querySelectorAll(
        ".pagination-buttons"
    );

    paginationContainers.forEach(function (container) {
        const paginationButtons = container.querySelectorAll("button");

        paginationButtons.forEach(function (button) {
            button.addEventListener("click", function () {
                const buttonText = this.textContent.trim();

                if (
                    buttonText === "" ||
                    buttonText === "..." ||
                    this.querySelector("i")
                ) {
                    return;
                }

                paginationButtons.forEach(function (paginationButton) {
                    paginationButton.classList.remove("active");
                });

                this.classList.add("active");

                showDashboardToast(
                    "Page " + buttonText + " selected."
                );
            });
        });
    });
});