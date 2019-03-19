function restrictCadastroPrazo() {
    let form = forms[$("#idScriptHiddenCesta").closest(".form-crud").attr("id")];
    db.exeRead("campanhas", form.data.campanha).then(data => {
        if(typeof data.prazo_para_cadastro === "string") {
            let d = data.prazo_para_cadastro.split("-");
            d = d[2] + "/" + d[1] + "/" + d[0];
            if (getCookie("setor") !== "admin" && new Date(data.prazo_para_cadastro).getTime() < new Date().getTime()) {
                let $div = $("div[data-column='produtos_da_campanha']").closest(".parent-input");
                $div.off("click", "div, button").find("div, button").removeAttr("onclick");
                $div.find(".addExtend, button").addClass("disabled").attr("disabled", "disabled").attr("title", "Prazo para Alterações Expirado (" + d + ")");
            }
        }
    });
}restrictCadastroPrazo();