$(function () {
    let form = forms[$("#idScriptHiddenLancamento").closest(".form-crud").attr("id")];

    form.$element.off("change", "select[data-column='situacao']").on("change", "select[data-column='situacao']", function () {
        let value = $(this).val();
        if (value === "2" || value === "4") {
            form.$element.find("textarea[data-column='descricao_do_problema']").closest(".parent-input").removeClass("hide");
            form.$element.find("textarea[data-column='descricao_do_problema']").focus();

            if(value === "2") {
                form.$element.find("input[data-column='prazo_da_pendencia']").closest(".parent-input").removeClass("hide");
                let dateNow = new Date();
                dateNow.setDate(dateNow.getDate() + 2);
                let dataAtual = dateNow.getFullYear() + "-" + zeroEsquerda(dateNow.getMonth() + 1) + "-" + zeroEsquerda(dateNow.getDate());
                form.data['prazo_da_pendencia'] = dataAtual;
                form.$element.find("input[data-column='prazo_da_pendencia']").val(dataAtual);
            } else {
                form.data['prazo_da_pendencia'] = "";
                form.$element.find("input[data-column='prazo_da_pendencia']").closest(".parent-input").addClass("hide");
            }
        } else {
            form.data['prazo_da_pendencia'] = "";
            form.$element.find("textarea[data-column='descricao_do_problema'], input[data-column='prazo_da_pendencia']").closest(".parent-input").addClass("hide");
        }
    });

    let value = form.$element.find("select[data-column='situacao']").val();
    if (value === "2" || value === "4") {
        form.$element.find("textarea[data-column='descricao_do_problema']").closest(".parent-input").removeClass("hide");

        if(value === "2")
            form.$element.find("input[data-column='prazo_da_pendencia']").closest(".parent-input").removeClass("hide");
    }
});
