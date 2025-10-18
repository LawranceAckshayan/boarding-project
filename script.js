// Enhanced client-side validation (Payment & basic inputs)
document.addEventListener('DOMContentLoaded', function () {
  const paymentForm = document.getElementById('paymentForm');
  if (!paymentForm) return;

  // Helper: Luhn checksum validation
  function luhnCheck(num) {
    const arr = num.split('').reverse().map(x => parseInt(x, 10));
    const sum = arr.reduce((acc, val, idx) => {
      if (idx % 2 === 1) {
        let dbl = val * 2;
        if (dbl > 9) dbl -= 9;
        return acc + dbl;
      }
      return acc + val;
    }, 0);
    return sum % 10 === 0;
  }

  // DOM refs
  const cardNumberInput = document.getElementById('card_number');
  const cvvInput = document.getElementById('cvv');
  const expiryInput = document.getElementById('expiry');

  // Inline error utility
  function setError(input, message) {
    let help = input.closest('.form-group')?.querySelector('.help-text');
    if (!help) {
      help = document.createElement('div');
      help.className = 'help-text error-text';
      input.closest('.form-group')?.appendChild(help);
    }
    help.textContent = message || '';
    if (message) input.classList.add('input-error'); else input.classList.remove('input-error');
  }

  // Numeric guards & formatting
  function onlyDigits(e) {
    e.target.value = e.target.value.replace(/\D/g, '');
  }

  if (cardNumberInput) {
    cardNumberInput.addEventListener('input', onlyDigits);
    cardNumberInput.setAttribute('maxlength', '19'); // allow spaces in future, but we strip to digits
    cardNumberInput.addEventListener('blur', () => {
      const digits = cardNumberInput.value.replace(/\D/g, '');
      if (digits.length < 13 || digits.length > 19) {
        setError(cardNumberInput, 'Card number must be 13–19 digits');
      } else if (!luhnCheck(digits)) {
        setError(cardNumberInput, 'Invalid card number (Luhn check failed)');
      } else {
        setError(cardNumberInput, '');
      }
    });
  }

  if (cvvInput) {
    cvvInput.addEventListener('input', onlyDigits);
    cvvInput.setAttribute('maxlength', '4');
    cvvInput.addEventListener('blur', () => {
      const digits = cvvInput.value.replace(/\D/g, '');
      if (!(digits.length === 3 || digits.length === 4)) {
        setError(cvvInput, 'CVV must be 3 or 4 digits');
      } else {
        setError(cvvInput, '');
      }
    });
  }

  if (expiryInput) {
    expiryInput.addEventListener('input', (e) => {
      let v = e.target.value.replace(/\D/g, '');
      if (v.length > 4) v = v.slice(0, 4);
      if (v.length >= 3) v = v.slice(0, 2) + '/' + v.slice(2);
      e.target.value = v;
    });

    expiryInput.addEventListener('blur', () => {
      const m = expiryInput.value.match(/^(0[1-9]|1[0-2])\/(\d{2})$/);
      if (!m) {
        setError(expiryInput, 'Use MM/YY format (e.g., 09/27)');
        return;
      }
      const month = parseInt(m[1], 10);
      const year2 = parseInt(m[2], 10);
      const now = new Date();
      let fullYear = 2000 + year2;
      // assume cards up to +15 years validity
      if (fullYear < now.getFullYear() - 5) fullYear += 100;
      const exp = new Date(fullYear, month - 1, 1);
      const endOfMonth = new Date(fullYear, month, 0);
      if (endOfMonth < new Date(now.getFullYear(), now.getMonth(), now.getDate())) {
        setError(expiryInput, 'Card is expired');
      } else {
        setError(expiryInput, '');
      }
    });
  }

  paymentForm.addEventListener('submit', function (e) {
    let hasError = false;

    if (cardNumberInput) {
      const digits = cardNumberInput.value.replace(/\D/g, '');
      if (digits.length < 13 || digits.length > 19 || !luhnCheck(digits)) {
        setError(cardNumberInput, 'Enter a valid card number');
        hasError = true;
      }
    }
    if (cvvInput) {
      const digits = cvvInput.value.replace(/\D/g, '');
      if (!(digits.length === 3 || digits.length === 4)) {
        setError(cvvInput, 'CVV must be 3 or 4 digits');
        hasError = true;
      }
    }
    if (expiryInput) {
      const m = expiryInput.value.match(/^(0[1-9]|1[0-2])\/(\d{2})$/);
      if (!m) {
        setError(expiryInput, 'Use MM/YY format');
        hasError = true;
      }
    }

    if (hasError) {
      e.preventDefault();
      const firstErr = paymentForm.querySelector('.input-error');
      if (firstErr) firstErr.focus();
    }
  });
});
