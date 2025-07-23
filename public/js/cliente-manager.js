// Funciones para manejo de clientes
window.ClienteManager = {
    // Datos de ciudades que se pasan desde el servidor
    ciudades: [],
    clienteId: null,
    clienteCiudadId: null,
    
    // Inicializar el manager con datos
    init: function(ciudadesData, clienteId, clienteCiudadId) {
        this.ciudades = ciudadesData || [];
        this.clienteId = clienteId || null;
        this.clienteCiudadId = clienteCiudadId || null;
        this.setupEventListeners();
        this.loadInitialCiudades();
    },
    
    // Configurar event listeners
    setupEventListeners: function() {
        const tipoClienteSelect = document.getElementById('tipo_cliente');
        const departamentoSelect = document.getElementById('id_departamento');
        
        if (tipoClienteSelect) {
            tipoClienteSelect.addEventListener('change', this.toggleRazonSocial);
        }
        
        if (departamentoSelect) {
            departamentoSelect.addEventListener('change', this.loadCiudades.bind(this));
        }
        
        // Verificar tipo de cliente al cargar la página
        this.toggleRazonSocial();
    },
    
    // Cargar ciudades iniciales (para modo edición)
    loadInitialCiudades: function() {
        if (this.clienteId && this.clienteCiudadId) {
            const departamentoSelect = document.getElementById('id_departamento');
            if (departamentoSelect && departamentoSelect.value) {
                this.loadCiudades();
            }
        }
    },
    
    // Mostrar/ocultar razón social según tipo de cliente
    toggleRazonSocial: function() {
        const tipoCliente = document.getElementById('tipo_cliente');
        const razonSocialGroup = document.getElementById('razon_social_group');
        
        if (tipoCliente && razonSocialGroup) {
            if (tipoCliente.value === 'juridico') {
                razonSocialGroup.classList.remove('d-none');
                razonSocialGroup.style.display = 'block';
            } else {
                razonSocialGroup.classList.add('d-none');
                razonSocialGroup.style.display = 'none';
            }
        }
    },
    
    // Cargar ciudades según departamento seleccionado
    loadCiudades: function() {
        const departamentoSelect = document.getElementById('id_departamento');
        const ciudadSelect = document.getElementById('id_ciudad');
        
        if (!departamentoSelect || !ciudadSelect) return;
        
        const departamentoId = departamentoSelect.value;
        ciudadSelect.innerHTML = '<option value="">Cargando...</option>';
        
        if (departamentoId) {
            const ciudadesFiltradas = this.ciudades.filter(ciudad => 
                ciudad.id_departamento == departamentoId
            );
            
            ciudadSelect.innerHTML = '<option value="">Seleccionar ciudad...</option>';
            
            ciudadesFiltradas.forEach(ciudad => {
                const option = document.createElement('option');
                option.value = ciudad.id;
                option.textContent = ciudad.nombre;
                
                // Seleccionar la ciudad actual si estamos en modo edición
                if (this.clienteCiudadId && ciudad.id == this.clienteCiudadId) {
                    option.selected = true;
                }
                
                ciudadSelect.appendChild(option);
            });
        } else {
            ciudadSelect.innerHTML = '<option value="">Seleccionar departamento primero...</option>';
        }
    }
};
