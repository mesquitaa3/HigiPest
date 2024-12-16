function limparPesquisa() {
    // Limpa a barra de pesquisa
    const searchInput = document.querySelector('input[name="search"]');
    searchInput.value = '';
        
    // Redireciona para a mesma página sem parâmetros de pesquisa
    window.location.href = window.location.pathname; // Redireciona para a URL atual sem parâmetros
}

// Limpar o campo de pesquisa ao carregar a página
window.onload = function() {
    document.getElementById("search_bar").value = "";
};