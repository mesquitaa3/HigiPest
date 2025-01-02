document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
    e.preventDefault();
    const targetId = this.getAttribute('data-target');
                
    //oculta todas as seções
    document.querySelectorAll('.conteudo-secao').forEach(section => {
        section.style.display = 'none';
    });
                
    //mostra a seção alvo
    document.getElementById(targetId).style.display = 'block';
                
    //atualiza a classe ativa no menu
    document.querySelectorAll('.nav-link').forEach(navLink => {
        navLink.classList.remove('active');
    });
    this.classList.add('active');
    });
});
