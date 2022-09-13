$(document).ready(() => {
    class Products {
        // Envia requisicao para adicionar um novo produto 
        addNewProduct = (e) => {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: 'api/api.php',
                data: $('#newProductForm').serialize(),
                success: (data) => {
                    this.displayProducts();
                    this.displayHistory();
                    if (data !== 'productCreated') {
                        let obj = JSON.parse(data);
                        let str = '';
                        $.each(obj, (key, value) => {
                            str += key+': '+value+'<br>';
                        })
                        $('#newProductMessages').html(str);
                    } else {
                        $('#nameProd').val('');
                        $('#codeProd').val('');
                        $('#inStorage').val('');
                        $('#prodPrice').val('');
                        $('#newProductMessages').html('');
                    }
                }
            });
        }

        // Envia requisicao para atualizar um produto
        updateProduct = (e) => {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: 'api/api.php',
                data: $('#updateProductForm').serialize(),
                success: (data) => {
                    this.displayProducts();
                    this.displayHistory();
                    if (data !== 'productUpdated') {
                        let obj = JSON.parse(data);
                        let str = '';
                        $.each(obj, (key, value) => {
                            str += key+': '+value+'<br/>';
                        });
                        $('#updateProductMessages').html(str);
                    } else {
                        this.displayUpdateProductDiv('close', null, e);
                        this.displayHistoryDiv('display');
                    }
                }
            });
        }

        // envia uma requisicao para deletar um ou mais produtos
        deleteProduct = () => {
            $.ajax({
                method: 'post',
                url: 'api/api.php',
                data: {deleteProducts: JSON.stringify(delProds)},
                success: (data) => {
                    delProds = [];
                    this.displayProducts();
                    this.displayHistory();
                } 
            });
        }

        // Exibe todos os rodutos
        displayProducts = () => {
            $.ajax({
                method: 'get',
                url: 'api/api.php',
                data: {displayProds: 1},
                success: (data) => {
                    let prod = JSON.parse(data);
                    let count = prod.length;
                    let str = '';
                    for (let i = 0; i < count; i++) {
                        str += '<input type="hidden" class="productId" value="'+prod[i]['id']+'">'
                        str += '<tr >'
                        str += '<td><input type="checkbox" id="deleteProd'+prod[i]['id']+'" value="'+prod[i]['id']+'"/></td>';
                        str += '<td>'+prod[i]['name']+'</td>';
                        str += '<td>'+prod[i]['code']+'</td>';
                        str += '<td>'+prod[i]['price']+'</td>';
                        str += '<td>'+prod[i]['in_storage']+'</td>';
                        str += '<td><button id="product'+prod[i]['id']+'" class="btn btnBlue">Ver estoque</button></td>';
                        str += '</tr>'
                    }
                    $('#storageItems').html(str);
                    
                    /**
                     * Seleciona o id de trodo os produtos para poder clicar em atualizar e 
                     * selecionar as checkbos dos produtos para deletar
                     */ 
                    document.querySelectorAll(".productId").forEach((values) => {
                        let val = values.value;
                        this.clickToDisplayUpadateDiv(val);
                        this.selectProductsToDelete(val);
                    });
                }
            });

            
        }

        // Adiciona o valor das checkboxes dos produtos para serem deletados
        selectProductsToDelete = (id) => {
            $('#deleteProd'+id).click((e) => {
                let val = e.target.value;
                if ($('#deleteProd'+id).is(':checked')) {
                    delProds.push(val);
                } else {
                    delProds.splice(delProds.indexOf(val), 1);
                }
            });
        }

        // Requisiçao para exibir historico do estoque
        displayHistory = () => {
            $.ajax({
                method: 'get',
                url: 'api/api.php',
                data: {displayHistory: 1},
                success: (data) => {
                    let array = JSON.parse(data);
                    let count = array.length;
                    let str = '';
                    for (let i = 0; i < count; i++) {
                        str += '<tr>';
                        str += '<td>'+array[i]['name']+'</td>';
                        str += '<td>'+array[i]['code']+'</td>';
                        str += '<td>'+array[i]['new_storage']+'</td>';
                        str += '<td>'+array[i]['updated_at']+'</td>';
                        str += '</tr>';
                    }
                    $('#productsHistory').html(str);
                }
            });
        }

        // Exibe div de historico
        displayHistoryDiv = (type) => {
            if (type ===  'display') {
                $('#storageHistory').show();
                $('#darkBackground').show();
            } else {
                $('#storageHistory').hide();
                $('#darkBackground').hide();
            }
        }

        // Exibe div para atualizar produto
        clickToDisplayUpadateDiv = (id) => {
            $('#product'+id).click(() => {
                this.displayUpdateProductDiv('display', id);
            });
        }

        // Exibe div para criação de um novo produto
        displayNewProductDiv = (type, e = null) => {
            if (e) {
                e.preventDefault();
            }
            $('#newProductMessages').html('');
            
            if (type === 'display') {
                $('#divAddNewProduct').show();
                $('#darkBackground').show();
            } else {
                $('#divAddNewProduct').hide();
                $('#darkBackground').hide();
            }
        }

        // Exibe div de atuallização de produto
        displayUpdateProductDiv = (type, id = null, e = null) => {
            if (e) {
                e.preventDefault();
            }
            
            if (type === 'display') {
                $('#productDetails').show();
                $('#darkBackground').show();
            } else {
                $('#productDetails').hide();
                $('#darkBackground').hide();
            }

            if (id) {
                this.displaySpecificProduct(id);
            }
        }

        // Preenche a dive de atulaizaçao dos produtos com os dados de determinado produto
        displaySpecificProduct = (id) => {
            $.ajax({
                method: 'post',
                url: 'api/api.php',
                data: {id: id},
                success: (data) => {
                    let prod = JSON.parse(data);
                    $('#productIdUp').val(prod['id']);
                    $('#nameUpProd').val(prod['name']);
                    $('#codeUpProd').val(prod['code']);
                    $('#inStorageUp').val(prod['in_storage']);
                    $('#prodUpPrice').val(prod['price']);
                }
            });
        }
    }
    var delProds = [];

    let prods = new Products();

    $('#newProductForm').submit((e) => {
        prods.addNewProduct(e);
    })

    $('#updateProductForm').submit((e) => {
        prods.updateProduct(e);
    })

    $('#deleteItems').click(() => {
        prods.deleteProduct();
    });

    $('#displayDivAddNewProduct').click(() => {
        prods.displayNewProductDiv('display');
    });

    $('#closeDivAddNewProduct').click((e) => {
        prods.displayNewProductDiv('close', e);
    });

    $('#displayHistoryDiv').click(() => {
        prods.displayHistoryDiv('display');
    });

    $('#closeStorageHistory').click(() => {
        prods.displayHistoryDiv('close');
    });

    $('#closeDivUpdateProductForm').click((e) => {
        prods.displayUpdateProductDiv('close', null, e);
    });

    prods.displayProducts();

    prods.displayHistory();
});
