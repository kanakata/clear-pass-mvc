const submit = document.querySelectorAll('input[type=submit]');

submit.forEach((btn) => {
  btn.addEventListener('click', () => {
    const inputs = document.querySelectorAll('input[type=text]');
    inputs.forEach((input) => {
      input.require = true;
    });
  });
});
