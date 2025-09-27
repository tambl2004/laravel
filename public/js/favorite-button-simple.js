// Favorite Button Handler - Simple Version
document.addEventListener('DOMContentLoaded', function() {
    console.log('Favorite button handler loaded');
    
    // Xử lý nút yêu thích
    document.querySelectorAll('.btn-favorite').forEach(button => {
        console.log('Found favorite button:', button);
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.dataset.productId;
            const isFavorited = this.classList.contains('favorited');
            const storeUrl = this.dataset.storeUrl;
            const destroyUrl = this.dataset.destroyUrl;
            
            console.log('Button clicked:', {
                productId: productId,
                isFavorited: isFavorited,
                storeUrl: storeUrl,
                destroyUrl: destroyUrl
            });
            
            // Hiển thị loading
            const originalIcon = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;
            
            // Gửi request
            const url = isFavorited ? destroyUrl : storeUrl;
            const method = isFavorited ? 'DELETE' : 'POST';
            
            console.log('Sending request to:', url, 'Method:', method);
            
            // Kiểm tra URL trước khi gửi request
            if (!url || url.includes('/favorites/') === false) {
                console.error('Invalid URL:', url);
                throw new Error('URL không hợp lệ');
            }
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    // Cập nhật trạng thái nút
                    if (isFavorited) {
                        this.classList.remove('favorited');
                        this.title = 'Thêm vào yêu thích';
                        // Cập nhật text cho nút lớn
                        const favoriteText = this.querySelector('.favorite-text');
                        if (favoriteText) {
                            favoriteText.textContent = 'Thêm vào yêu thích';
                        }
                        // Cập nhật icon cho nút lớn
                        const icon = this.querySelector('i');
                        if (icon) {
                            icon.className = 'far fa-heart me-2';
                        }
                    } else {
                        this.classList.add('favorited');
                        this.title = 'Xóa khỏi yêu thích';
                        // Cập nhật text cho nút lớn
                        const favoriteText = this.querySelector('.favorite-text');
                        if (favoriteText) {
                            favoriteText.textContent = 'Đã yêu thích';
                        }
                        // Cập nhật icon cho nút lớn
                        const icon = this.querySelector('i');
                        if (icon) {
                            icon.className = 'fas fa-heart me-2';
                        }
                    }
                    
                    // Hiển thị thông báo thành công
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        alert(data.message);
                    }
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Hiển thị thông báo lỗi
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: error.message || 'Không thể cập nhật yêu thích',
                        confirmButtonText: 'Thử lại'
                    });
                } else {
                    alert('Lỗi: ' + (error.message || 'Không thể cập nhật yêu thích'));
                }
            })
            .finally(() => {
                this.innerHTML = originalIcon;
                this.disabled = false;
            });
        });
    });
});
