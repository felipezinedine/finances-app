document.addEventListener("DOMContentLoaded", () => {

    const masks = {

        cpf(value) {
            return value
                .replace(/\D/g, "")
                .replace(/(\d{3})(\d)/, "$1.$2")
                .replace(/(\d{3})(\d)/, "$1.$2")
                .replace(/(\d{3})(\d{1,2})$/, "$1-$2")
                .slice(0, 14);
        },

        cnpj(value) {
            return value
                .replace(/\D/g, "")
                .replace(/(\d{2})(\d)/, "$1.$2")
                .replace(/(\d{3})(\d)/, "$1.$2")
                .replace(/(\d{3})(\d)/, "$1/$2")
                .replace(/(\d{4})(\d)/, "$1-$2")
                .slice(0, 18);
        },

        rg(value) {
            return value.replace(/\D/g, "").slice(0, 10);
        },

        phone(value) {
            return value
                .replace(/\D/g, "")
                .replace(/(\d{2})(\d)/, "($1) $2")
                .replace(/(\d{4})(\d)/, "$1-$2")
                .slice(0, 14);
        },

        mobile(value) {
            return value
                .replace(/\D/g, "")
                .replace(/(\d{2})(\d)/, "($1) $2")
                .replace(/(\d{5})(\d)/, "$1-$2")
                .slice(0, 15);
        },

        zip_code(value) {
            return value
                .replace(/\D/g, "")
                .replace(/(\d{5})(\d)/, "$1-$2")
                .slice(0, 9);
        },

        birthday(value) {
            return value
                .replace(/\D/g, "")
                .replace(/(\d{2})(\d)/, "$1/$2")
                .replace(/(\d{2})(\d)/, "$1/$2")
                .slice(0, 10);
        },

        money(value) {
            let v = value.replace(/\D/g, "");

            if (!v) return "";

            v = (Number(v) / 100).toFixed(2) + "";

            v = v.replace(".", ",");

            return v.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    };


    function applyMask(selector, maskFn) {

        const elements = document.querySelectorAll(selector);

        if (!elements.length) return;

        elements.forEach(el => {

            if (el.dataset.maskApplied) return;

            el.dataset.maskApplied = 'true';

            const apply = () => {

                const value = el.value;

                const masked = maskFn(value);

                if (value !== masked) {
                    el.value = masked;
                }
            };

            el.addEventListener('input', apply);

            el.addEventListener('paste', () => {
                setTimeout(apply, 0);
            });

            el.addEventListener('change', apply);

        });

    }


    // ID masks
    applyMask("#cpf", masks.cpf);
    applyMask("#rg", masks.rg);
    applyMask("#phone", masks.phone);
    applyMask("#mobile", masks.mobile);
    applyMask("#zip_code", masks.zip_code);
    applyMask("#cnpj", masks.cnpj);
    applyMask("#birthday", masks.birthday);


    // CLASS masks
    applyMask(".cpf", masks.cpf);
    applyMask(".rg", masks.rg);
    applyMask(".phone", masks.phone);
    applyMask(".mobile", masks.mobile);
    applyMask(".zip_code", masks.zip_code);
    applyMask(".cnpj", masks.cnpj);
    applyMask(".birthday", masks.birthday);


    document.querySelectorAll(".money").forEach((input) => {

        input.addEventListener("input", (e) => {

            e.target.value = masks.money(e.target.value);

        });

        if (input.value) {

            input.value = masks.money(input.value);

        }

    });

});