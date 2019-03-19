$(function () {
    let form = forms[$("#idScriptHiddenLancamento").closest(".form-crud").attr("id")];

    form.$element.off("change", "select[data-column='situacao']").on("change", "select[data-column='situacao']", function () {
        let value = $(this).val();
        if (value === "2" || value === "4") {
            form.$element.find("textarea[data-column='descricao_do_problema']").closest(".parent-input").removeClass("hide");
            form.$element.find("textarea[data-column='descricao_do_problema']").focus();
        } else {
            form.$element.find("textarea[data-column='descricao_do_problema']").closest(".parent-input").addClass("hide");
        }
    });

    let value = form.$element.find("select[data-column='situacao']").val();
    if (value === "2")
        form.$element.find("textarea[data-column='descricao_do_problema']").closest(".parent-input").removeClass("hide");
});
