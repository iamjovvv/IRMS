document.addEventListener('DOMContentLoaded', () => {
    const validRadio = document.getElementById('validOption');
    const invalidRadio = document.getElementById('invalidOption');

    const prioritySection = document.getElementById('prioritySection');
    const invalidReasonSection = document.getElementById('invalidReasonSection');

    const priorityRadios = prioritySection.querySelectorAll('input[type="radio"]');
    const invalidReasonTextarea = document.getElementById('invalid_reason');

    const submitBtn = document.querySelector('button[type="submit"]');

    submitBtn.disabled = true;

    function updateSubmitState(){
        if (validRadio.checked){
            const hasPriority = [...priorityRadios].some(r =>r.checked);
            submitBtn.disabled = !hasPriority;

        }

        if (invalidRadio.checked){
            submitBtn.disabled = invalidReasonTextarea.value.trim() === '';
        }
    }

    disablePriority();
    disableInvalidReason();

    function disablePriority(){
        prioritySection.classList.add('is-disabled');
        priorityRadios.forEach(radio => radio.checked = false);
    }

    function enablePriority(){
        prioritySection.classList.remove('is-disabled');
    }

    function disableInvalidReason(){
        invalidReasonSection.classList.add('is-disabled');
        invalidReasonTextarea.value = '';
    }

    function enableInvalidReason(){
        invalidReasonSection.classList.remove('is-disabled');
    }

    validRadio.addEventListener('change', () => {
        if (validRadio.checked){
            enablePriority();
            disableInvalidReason();
            updateSubmitState();
        }
    });

    invalidRadio.addEventListener('change', () => 
        {
        if (invalidRadio.checked) {
            enableInvalidReason();
            disablePriority();
            updateSubmitState();
        }
    });

    priorityRadios.forEach(radio => {
        radio.addEventListener('change', updateSubmitState);

    });

    invalidReasonTextarea.addEventListener('input', updateSubmitState);

    



});