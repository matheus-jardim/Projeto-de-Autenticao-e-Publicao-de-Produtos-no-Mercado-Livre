document.getElementById('productForm').addEventListener('submit', (e) => {
    e.preventDefault();
    document.getElementById('form_status').innerHTML = '';

    let formData = new FormData(e.target);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "form_action.php", true);
    xhr.onload = () => {
        if (xhr.status === 200 && (xhr.responseText !== 'Dados inválidos do formulário' && xhr.responseText !== 'Erro ao publicar o produto' && xhr.responseText !== 'Erro na chamada à API do Mercado Livre' && xhr.responseText !== "Por favor, preencha todos os campos obrigatórios.")) {
            console.log(xhr.responseText);
            document.getElementById('form_info').innerText = xhr.responseText;
            document.getElementById('form-submit').style.display = 'none';
            document.getElementById('new_form').style.display = 'block';
        } else {
            document.getElementById('form_info').innerText = 'Ocorreu um problema no envio. Tente novamente mais tarde!';
            document.getElementById('form_status').innerHTML = `<h3>${xhr.responseText}</h3>`;
        }
    };
    xhr.send(formData);
    
    document.getElementById('title').setAttribute('disabled', 'disabled');
    document.getElementById('price').setAttribute('disabled', 'disabled');
    document.getElementById('stock').setAttribute('disabled', 'disabled');
    document.getElementById('category').setAttribute('disabled', 'disabled');
})

document.getElementById('title').addEventListener('input', (e) => {

    if (e.target.value !== "") {
        let title = encodeURIComponent(e.target.value);
        let formData = new FormData();
        formData.append('title', title);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", 'form_category.php', true);
        xhr.onload = () => {
            if (xhr.status === 200) {
                let dataCategories = JSON.parse(xhr.responseText);

                let categorys = document.getElementById('category');
                categorys.innerHTML = '';

                if (dataCategories.length > 0) {
                    console.log(dataCategories);

                    for (let i in dataCategories) {
                        let optionElement = document.createElement("option");

                        optionElement.value = dataCategories[i]['category_id'];
                        optionElement.text = dataCategories[i]['domain_name'];

                        categorys.appendChild(optionElement);
                    }
                    let categoryNotFound = document.createElement("option");
                    categoryNotFound.value = "NOTFOUND"
                    categoryNotFound.text = "CATEGORIA NÃO ENCONTRADA";
                    categorys.append(categoryNotFound)
                }
            }
        }
        xhr.send(formData);
    }
})

document.getElementById('new_form').addEventListener('click', (e) => {
    document.getElementById('title').removeAttribute('disabled');
    document.getElementById('price').removeAttribute('disabled');
    document.getElementById('stock').removeAttribute('disabled');
    document.getElementById('category').removeAttribute('disabled');

    document.getElementById('title').value = '';
    document.getElementById('price').value = '';
    document.getElementById('stock').value = '';
    document.getElementById('category').innerHTML = '<option value="TITULO" disabled selected>PREENCHA O TÍTULO PRIMEIRO</option>';  
    
    document.getElementById('new_form').style.display = 'none';
    document.getElementById('form-submit').style.display = 'block';
    document.getElementById('form_info').innerText = '';
})