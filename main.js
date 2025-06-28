// Main JavaScript for Mkristoh Rental

document.addEventListener('DOMContentLoaded', function() {
    // Load cars on homepage
    loadCars();
    
    // Initialize countdown timers
    initCountdownTimers();
});

// Function to initialize countdown timers for rental cancellation
function initCountdownTimers() {
    const countdownElements = document.querySelectorAll('.countdown-timer');
    
    countdownElements.forEach(element => {
        const remainingTime = parseInt(element.getAttribute('data-remaining'));
        const rentalId = element.getAttribute('data-rental-id');
        
        if (remainingTime > 0) {
            startCountdown(element, remainingTime, rentalId);
        } else {
            // Time expired, disable cancel button
            disableCancelButton(rentalId);
        }
    });
}

// Function to start countdown timer
function startCountdown(element, remainingTime, rentalId) {
    let timeLeft = remainingTime;
    
    const timer = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(timer);
            disableCancelButton(rentalId);
            return;
        }
        
        const hours = Math.floor(timeLeft / 3600);
        const minutes = Math.floor((timeLeft % 3600) / 60);
        const seconds = timeLeft % 60;
        
        // Update display
        element.querySelector('.hours').textContent = hours.toString().padStart(2, '0');
        element.querySelector('.minutes').textContent = minutes.toString().padStart(2, '0');
        element.querySelector('.seconds').textContent = seconds.toString().padStart(2, '0');
        
        timeLeft--;
    }, 1000);
}

// Function to disable cancel button when time expires
function disableCancelButton(rentalId) {
    const rentalCard = document.querySelector(`[data-rental-id="${rentalId}"]`).closest('.card');
    const countdownElement = rentalCard.querySelector('.countdown-timer');
    const cancelForm = rentalCard.querySelector('form');
    const cancelButton = rentalCard.querySelector('button[type="submit"]');
    
    if (countdownElement) {
        countdownElement.innerHTML = '<span class="text-danger">00:00:00</span>';
    }
    
    if (cancelForm) {
        // Replace form with disabled button
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-secondary py-2 mb-2';
        alertDiv.innerHTML = '<small class="text-muted"><i class="fas fa-clock me-1"></i>Cancellation time expired</small>';
        
        const disabledButton = document.createElement('button');
        disabledButton.className = 'btn btn-secondary w-100';
        disabledButton.disabled = true;
        disabledButton.innerHTML = '<i class="fas fa-lock me-2"></i>Cannot Cancel';
        
        cancelForm.parentNode.replaceChild(alertDiv, cancelForm);
        alertDiv.parentNode.appendChild(disabledButton);
    }
}

// Function to load cars dynamically
function loadCars() {
    const carsContainer = document.getElementById('cars-container');
    if (!carsContainer) return;

    // Sample car data with engine specifications (in real application, this would come from PHP/API)
    const cars = [
        {
            id: 1,
            name: 'Toyota Camry',
            category: 'Sedan',
            price: 50,
            image: 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            features: ['5 Seats', 'Automatic', 'Air Conditioning', 'Bluetooth'],
            engine: '2.5L 4-Cylinder, 203 HP, 184 lb-ft Torque, 8-Speed Automatic, FWD, 28 City / 39 Highway MPG',
            available: true
        },
        {
            id: 2,
            name: 'Honda CR-V',
            category: 'SUV',
            price: 70,
            image: 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            features: ['7 Seats', 'Automatic', '4WD', 'Navigation'],
            engine: '1.5L Turbo 4-Cylinder, 190 HP, 179 lb-ft Torque, CVT Transmission, AWD, 27 City / 32 Highway MPG',
            available: true
        },
        {
            id: 3,
            name: 'BMW 3 Series',
            category: 'Luxury',
            price: 120,
            image: 'https://images.unsplash.com/photo-1555215695-3004980ad54e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            features: ['5 Seats', 'Automatic', 'Leather Seats', 'Premium Sound'],
            engine: '2.0L Turbo 4-Cylinder, 255 HP, 295 lb-ft Torque, 8-Speed Automatic, RWD, 23 City / 33 Highway MPG',
            available: true
        },
        {
            id: 4,
            name: 'Ford Mustang',
            category: 'Sports',
            price: 150,
            image: 'https://images.unsplash.com/photo-1582639510494-c80b5de9f148?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            features: ['4 Seats', 'Manual', 'Sports Mode', 'Premium Audio'],
            engine: '5.0L V8, 450 HP, 410 lb-ft Torque, 6-Speed Manual, RWD, 15 City / 24 Highway MPG',
            available: true
        },
        {
            id: 5,
            name: 'Mercedes-Benz S-Class',
            category: 'Luxury',
            price: 200,
            image: 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            features: ['5 Seats', 'Automatic', 'Massage Seats', 'Panoramic Roof'],
            engine: '3.0L Turbo 6-Cylinder, 362 HP, 369 lb-ft Torque, 9-Speed Automatic, AWD, 20 City / 29 Highway MPG',
            available: true
        },
        {
            id: 6,
            name: 'Jeep Wrangler',
            category: 'Off-Road',
            price: 80,
            image: 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            features: ['5 Seats', 'Manual', '4WD', 'Removable Top'],
            engine: '3.6L V6, 285 HP, 260 lb-ft Torque, 6-Speed Manual, 4WD, 17 City / 23 Highway MPG',
            available: true
        }
    ];

    carsContainer.innerHTML = '';

    cars.forEach(car => {
        const carCard = createCarCard(car);
        carsContainer.appendChild(carCard);
    });
}

// Function to create car card
function createCarCard(car) {
    const col = document.createElement('div');
    col.className = 'col-lg-4 col-md-6 mb-4';
    
    // Create engine info HTML if available
    const engineInfo = car.engine ? `
        <div class="mb-3">
            <h6 class="text-dark mb-2 small">
                <i class="fas fa-cog me-1"></i>Engine
            </h6>
            <p class="text-muted small mb-2">${car.engine}</p>
        </div>
    ` : '';
    
    // Create features HTML
    const featuresHtml = car.features.map(feature => 
        `<span class="badge bg-light text-dark me-1 mb-1 small">${feature}</span>`
    ).join('');
    
    col.innerHTML = `
        <div class="card car-card h-100">
            <img src="${car.image}" class="card-img-top" alt="${car.name}">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title mb-0">${car.name}</h5>
                    <span class="badge bg-primary">${car.category}</span>
                </div>
                <p class="car-price mb-3">$${car.price}/day</p>
                
                ${engineInfo}
                
                <div class="car-features mb-3">
                    <h6 class="text-dark mb-2 small">
                        <i class="fas fa-star me-1"></i>Features
                    </h6>
                    ${featuresHtml}
                </div>
                
                <div class="mt-auto">
                    ${car.available ? 
                        `<button class="btn btn-primary w-100" onclick="rentCar(${car.id})">
                            <i class="fas fa-car me-2"></i>Rent Now
                        </button>` :
                        `<button class="btn btn-secondary w-100" disabled>
                            <i class="fas fa-times me-2"></i>Not Available
                        </button>`
                    }
                </div>
            </div>
        </div>
    `;
    
    return col;
}

// Function to handle car rental
function rentCar(carId) {
    // Check if user is logged in
    if (!isLoggedIn()) {
        window.location.href = 'login.php';
        return;
    }
    
    // Redirect to rental form
    window.location.href = `rental-form.php?car_id=${carId}`;
}

// Function to check if user is logged in
function isLoggedIn() {
    // Check for session or localStorage
    return localStorage.getItem('user_id') || sessionStorage.getItem('user_id');
}

// Function to handle logout
function logout() {
    // Clear session/localStorage
    localStorage.removeItem('user_id');
    localStorage.removeItem('user_name');
    sessionStorage.removeItem('user_id');
    sessionStorage.removeItem('user_name');
    
    // Redirect to home
    window.location.href = 'index.php';
}

// Function to show loading spinner
function showLoading(element) {
    element.innerHTML = '<span class="loading"></span> Loading...';
    element.disabled = true;
}

// Function to hide loading spinner
function hideLoading(element, originalText) {
    element.innerHTML = originalText;
    element.disabled = false;
}

// Function to show alert
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert at the top of the body
    document.body.insertBefore(alertDiv, document.body.firstChild);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Function to format date
function formatDate(date) {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Function to format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// Function to validate email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Function to validate phone
function validatePhone(phone) {
    const re = /^[\+]?[1-9][\d]{0,15}$/;
    return re.test(phone.replace(/\s/g, ''));
}

// Function to validate password
function validatePassword(password) {
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
    const re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]{8,}$/;
    return re.test(password);
}

// Function to handle form submission with loading state
function handleFormSubmission(form, submitButton, successCallback) {
    const originalText = submitButton.innerHTML;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        showLoading(submitButton);
        
        // Simulate form submission (replace with actual AJAX call)
        setTimeout(() => {
            hideLoading(submitButton, originalText);
            if (successCallback) {
                successCallback();
            }
        }, 2000);
    });
}

// Export functions for use in other scripts
window.MkristohRental = {
    loadCars,
    createCarCard,
    rentCar,
    isLoggedIn,
    logout,
    showLoading,
    hideLoading,
    showAlert,
    formatDate,
    formatCurrency,
    validateEmail,
    validatePhone,
    validatePassword,
    handleFormSubmission
}; 