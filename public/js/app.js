// --- Manipulação do Modal de Vagas ---
function openEditModal(vaga, actionUrl) {
    document.getElementById('editForm').action = actionUrl;

    document.getElementById('edit-cargo').value = vaga.cargo || '';
    document.getElementById('edit-empresa').value = vaga.empresa || '';

    if (vaga.data) {
        let formattedDate = vaga.data.toString().substring(0, 10);
        document.getElementById('edit-data').value = formattedDate;
    } else {
        document.getElementById('edit-data').value = '';
    }

    document.getElementById('edit-status').value = vaga.status || 'aplicado';
    document.getElementById('edit-link').value = vaga.link || '';
    document.getElementById('edit-notas').value = vaga.notas || '';
    document.getElementById('edit-salario').value = vaga.salario || '';

    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// --- Manipulação do Widget de Dicas ---
function editDica(id, actionUrl) {
    const descricao = document.getElementById('dica-texto-' + id).innerText.trim();
    const form = document.getElementById('dica-form');

    form.action = actionUrl;
    document.getElementById('dica-method').value = 'PUT';
    document.getElementById('dica-descricao').value = descricao;
    document.getElementById('dica-submit-btn').innerText = 'Atualizar';
    document.getElementById('dica-cancel-btn').style.display = 'inline-block';
}

function resetDicaForm(defaultActionUrl) {
    const form = document.getElementById('dica-form');

    form.action = defaultActionUrl;
    document.getElementById('dica-method').value = 'POST';
    document.getElementById('dica-descricao').value = '';
    document.getElementById('dica-submit-btn').innerText = 'Salvar';
    document.getElementById('dica-cancel-btn').style.display = 'none';
}

// Global Event Listener para fechar o modal de vagas clicando no backdrop
window.addEventListener('click', function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        closeEditModal();
    }
});
