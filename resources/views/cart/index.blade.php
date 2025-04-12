@extends('layout.app')

@section('content')

    <div class="container mt-4">
        <div>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorAlert">
                    {{ session('successDelete') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <h2 class="mb-4">Mon Panier</h2>

        @if($cartItems->isEmpty())
            <div class="alert alert-info">
                Votre panier est vide.
            </div>
        @else
            <form action="{{ route('order.store') }}" method="POST" id="orderForm">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input" onclick="toggleSelectAll(this)">
                            </th>
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cartItems as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" class="form-check-input item-checkbox">
                                </td>
                                <td>
                                    <img src="{{ asset('storage/' .$item->livre->image) }}" alt="{{ $item->livre->titre }}" class="img-thumbnail" style="width: 80px;">
                                </td>
                                <td>{{ $item->livre->titre }}</td>
                                <td>{{ $item->livre->categorie->libelle }}</td>
                                <td>{{ number_format($item->livre->prix, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    <div class="quantity-wrapper">
                                        <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" min="1" data-item-id="{{ $item->id }}">
                                        <button type="button" class="btn btn-sm btn-outline-primary update-quantity" data-item-id="{{ $item->id }}" style="display: none;">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm delete-item" data-item-id="{{ $item->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <strong>Total sélectionné :</strong>
                        <span id="selectedTotal">0 FCFA</span>
                    </div>
                    <button type="submit" id="orderButton" class="btn btn-primary" disabled>
                        Commander
                    </button>
                </div>
            </form>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction pour mettre à jour le total
            function updateTotal() {
                let total = 0;
                const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');

                checkedBoxes.forEach(checkbox => {
                    const row = checkbox.closest('tr');
                    const price = parseFloat(row.querySelector('td:nth-child(5)').textContent.replace(' FCFA', '').replace(/\s/g, ''));
                    const quantity = parseInt(row.querySelector('input[name="quantity"]').value);

                    total += price * quantity;
                });

                document.getElementById('selectedTotal').textContent = total.toLocaleString() + ' FCFA';

                // Activer/désactiver le bouton de commande en fonction de la sélection
                document.getElementById('orderButton').disabled = checkedBoxes.length === 0;
            }

            // Ajouter des écouteurs d'événements pour les cases à cocher et les quantités
            document.querySelectorAll('.item-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateTotal);
            });

            document.querySelectorAll('input[name="quantity"]').forEach(input => {
                input.addEventListener('change', function() {
                    // Afficher le bouton de mise à jour
                    const updateBtn = this.parentNode.querySelector('.update-quantity');
                    updateBtn.style.display = 'inline-block';

                    updateTotal();
                });
            });

            // Gérer les boutons de mise à jour de quantité
            document.querySelectorAll('.update-quantity').forEach(button => {
                button.addEventListener('click', function() {
                    const itemId = this.getAttribute('data-item-id');
                    const quantityInput = this.parentNode.querySelector('input[name="quantity"]');

                    // Créer et soumettre un formulaire pour la mise à jour
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ url('cart/update') }}/" + itemId;
                    form.style.display = 'none';

                    // Ajouter le token CSRF
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = "{{ csrf_token() }}";
                    form.appendChild(csrfToken);

                    // Ajouter la quantité
                    const quantityField = document.createElement('input');
                    quantityField.type = 'hidden';
                    quantityField.name = 'quantity';
                    quantityField.value = quantityInput.value;
                    form.appendChild(quantityField);

                    // Ajouter le formulaire au document et le soumettre
                    document.body.appendChild(form);
                    form.submit();
                });
            });

            // Gérer les boutons de suppression
            document.querySelectorAll('.delete-item').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
                        const itemId = this.getAttribute('data-item-id');

                        // Créer et soumettre un formulaire pour la suppression
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = "{{ route('cart.remove', '') }}/" + itemId;
                        form.style.display = 'none';

                        // Ajouter le token CSRF
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = "{{ csrf_token() }}";
                        form.appendChild(csrfToken);

                        // Ajouter la méthode DELETE
                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';
                        form.appendChild(methodField);

                        // Ajouter le formulaire au document et le soumettre
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });

            // Fonction pour la sélection de tous les éléments
            window.toggleSelectAll = function(source) {
                const checkboxes = document.querySelectorAll('.item-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = source.checked;
                });
                updateTotal();
            };

            // Initialiser le total
            updateTotal();
        });
    </script>
@endsection
