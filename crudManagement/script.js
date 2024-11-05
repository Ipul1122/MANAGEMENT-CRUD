function editUser(user) {
    $('#id').val(user.id);
    $('#username').val(user.username);
    $('#password').val(''); 
    $('#namaLengkap').val(user.namaLengkap);
    $('#email').val(user.email);
    $('#telepon').val(user.telepon);
    if (user.banned) {
        $('#bannedYes').prop('checked', true);
    } else {
        $('#bannedNo').prop('checked', true);
    }
    $('#aksesAdmin').prop('checked', user.akses & 1);
    $('#aksesOperator').prop('checked', user.akses & 2);
    $('#userModal').modal('show');
}

function resetForm() {
    $('#userForm')[0].reset();
    $('#id').val('');
    $('#aksesAdmin').prop('checked', false);
    $('#aksesOperator').prop('checked', false);
}