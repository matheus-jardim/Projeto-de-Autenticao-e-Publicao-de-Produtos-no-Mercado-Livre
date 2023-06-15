document.getElementById('productForm').addEventListener('submit', async (e)=>{
    e.preventDefault();

    let title = document.getElementById('title').value;
    let category = document.getElementById('category').value;
    let price = parseFloat(document.getElementById('price').value);
    let stock = document.getElementById('stock').value;

    let response = await fetch('/publish-product', {
        method: 'POST',
        body: JSON.stringify({title, category, price, stock}),
        headers: {
            'Content-Type': 'application/json'
        }
    });
    let json = await response.json();

    if(json.status == true) {
        document.getElementById(form_info).innerText = 'Produto publicado com sucesso!';
    } else {
        document.getElementById(form_info).innerText = 'Ocorreu um erro ao publicar o produto.';
    }
})