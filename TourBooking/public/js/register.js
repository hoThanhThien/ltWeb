$(document).ready(function () {
    $('.form-register').on('submit', function (e) {
        let password = $('#userPassword').val();
        let confirmPassword = $('#confirmPassword').val();

        if (password !== confirmPassword) {
            e.preventDefault(); // Ngăn form submit
            $('#passwordError').text('Mật khẩu xác nhận không khớp.');
        }
    });

    // Xóa lỗi khi người dùng nhập lại
    $('#confirmPassword').on('input', function () {
        $('#passwordError').text('');
    });
});
// Hiển thị thông báo lỗi nếu có
if (typeof errorMessage !== 'undefined' && errorMessage) {
    alert(errorMessage);
}
