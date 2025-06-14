document.addEventListener('DOMContentLoaded', () => {
    const search = document.getElementById('search');
    const toggle = document.getElementById('mostrarPagasSwitch');
    const linhas = document.querySelectorAll('#tabelaDividas tr');

    // Função de filtragem local com base no texto e no status de pagamento
    function filtrar() {
        const termo = search.value.toLowerCase();
        const mostrarPagas = toggle.checked;

        linhas.forEach(linha => {
            const texto = linha.textContent.toLowerCase();
            const status = linha.getAttribute('data-status');

            const condicaoTexto = texto.includes(termo);
            const condicaoStatus = mostrarPagas || status == '0';  // '0' é não paga

            linha.style.display = (condicaoTexto && condicaoStatus) ? '' : 'none';
        });
    }

    // Função para sincronizar a URL com o estado do switch
    function atualizarURL() {
        const url = new URL(window.location.href);
        if (toggle.checked) {
            url.searchParams.set('mostrarPagas', '1');
        } else {
            url.searchParams.delete('mostrarPagas');
        }
        window.history.replaceState({}, '', url);
    }

    // Event listeners para os filtros
    search.addEventListener('input', filtrar);
    toggle.addEventListener('change', () => {
        atualizarURL(); // Atualiza a URL com ou sem mostrarPagas
        window.location.reload(); // Recarrega a página para aplicar o filtro no backend
    });
    

    // Sincroniza o estado do switch com a URL ao carregar a página
    if (new URL(window.location.href).searchParams.has('mostrarPagas')) {
        toggle.checked = true;
    }
});
