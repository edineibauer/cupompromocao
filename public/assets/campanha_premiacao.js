function campanhaPremiacao() {
    let form = forms[$("#idScriptHiddenCampanhaPremiacao").closest(".form-crud").attr("id")];
    let data = form.data;
    if (typeof data.divulgacao === "string") {
        let d = data.divulgacao.split("-");
        d = d[2] + "/" + d[1] + "/" + d[0];
        if (getCookie("setor") !== "admin" && new Date(data.divulgacao).getTime() < new Date().getTime()) {
            let $div = $("div[data-column='premios']").closest(".parent-input");
            $div.off("click", "div, button").find("div, button").removeAttr("onclick");
            $div.find(".addExtend, button").addClass("disabled").attr("disabled", "disabled").attr("title", "Prazo para adicionar Prêmios Expirado (" + d + ")");
        }
    }
}campanhaPremiacao();