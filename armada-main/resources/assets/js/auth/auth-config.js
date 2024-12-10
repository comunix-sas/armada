// Configuración para manejar el token
const handleAuthToken = {
    setToken: (token) => {
        localStorage.setItem('auth_token', token);
        console.log('Token almacenado:', token);
    },

    getToken: () => {
        return localStorage.getItem('auth_token');
    },

    removeToken: () => {
        localStorage.removeItem('auth_token');
    },

    // Configurar los headers para las peticiones ajax
    getHeaders: () => {
        const token = handleAuthToken.getToken();
        return {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        };
    }
};

// Configurar Ajax globalmente para incluir el token en todas las peticiones
$.ajaxSetup({
    beforeSend: function(xhr) {
        const token = handleAuthToken.getToken();
        if (token) {
            xhr.setRequestHeader('Authorization', `Bearer ${token}`);
        }
    }
});
