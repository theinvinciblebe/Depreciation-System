// Sweet alert script with multi-language support (ENG / KH)
$(document).ready(function () {
    function getTrans(key, fallback) {
        if (window.APP_TRANSLATIONS && window.APP_TRANSLATIONS[key]) {
            return window.APP_TRANSLATIONS[key];
        }
        return fallback;
    }

    // Handle Delete button click
    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();
        let form = $(this).closest("form");
        Swal.fire({
            title: getTrans('swal_confirm_title', "Are you sure?"),
            text: getTrans('swal_confirm_delete_text', "This action cannot be undone!"),
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#f43f5e",
            cancelButtonColor: "#64748b",
            confirmButtonText: getTrans('swal_yes_delete', "Yes, delete it!"),
            cancelButtonText: getTrans('swal_cancel', "Cancel")
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Handle Add button click
    $(document).on('click', '.add-btn', function (e) {
        e.preventDefault();
        let form = $(this).closest("form");
        Swal.fire({
            title: window.APP_LANG === 'kh' ? "បន្ថែមទិន្នន័យថ្មី" : "Add New data",
            text: window.APP_LANG === 'kh' ? "តើអ្នកប្រាកដថាចង់បន្ថែមទិន្នន័យថ្មីមែនទេ?" : "Are you sure you want to add new data?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#4f46e5",
            cancelButtonColor: "#64748b",
            confirmButtonText: window.APP_LANG === 'kh' ? "បាទ/ចាស បន្ថែម!" : "Yes, add it!",
            cancelButtonText: getTrans('swal_cancel', "Cancel"),
            inputValidator: (value) => {
                if (!value) {
                    return window.APP_LANG === 'kh' ? "អ្នកត្រូវបញ្ចូលអ្វីមួយ!" : "You need to enter something!";
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr("action"),
                    method: form.attr("method"),
                    data: form.serialize(),
                    success: function (response) {
                        Swal.fire({
                            title: window.APP_LANG === 'kh' ? "ជោគជ័យ" : "Success",
                            text: window.APP_LANG === 'kh' ? "ទិន្នន័យត្រូវបានបន្ថែមដោយជោគជ័យ!" : "Data added successfully!",
                            icon: "success",
                            confirmButtonColor: "#4f46e5",
                        }).then(() => {
                            window.history.back();
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        });
                    },
                    error: function (error) {
                        Swal.fire({
                            title: window.APP_LANG === 'kh' ? "កំហុស" : "Error",
                            text: window.APP_LANG === 'kh' ? "មានបញ្ហាក្នុងការបន្ថែមទិន្នន័យ។" : "An error occurred while adding data.",
                            icon: "error",
                            confirmButtonColor: "#f43f5e",
                        });
                    }
                });
            }
        });
    });

    // Handle logout button click
    $(document).on('click', '.logout-btn', function (e) {
        e.preventDefault();
        let form = $(this).closest("form");

        Swal.fire({
            title: getTrans('swal_logout_title', "Are you sure?"),
            text: getTrans('swal_logout_text', "You will be logged out of your session."),
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#f43f5e",
            cancelButtonColor: "#64748b",
            confirmButtonText: getTrans('swal_yes_logout', "Yes, Sign Out"),
            cancelButtonText: getTrans('swal_cancel', "Cancel")
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});