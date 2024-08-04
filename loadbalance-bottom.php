<script>
document.addEventListener('DOMContentLoaded', (event) => {
    // پیدا کردن المان li با href و data-page-id مورد نظر
    setTimeout(()=>{
        const targetAnchor = document.querySelector('a[href="#maintenance_system_log"][data-page-id="maintenance_system_log"]');

    if (targetAnchor) {
        const targetElement = targetAnchor.parentNode;
        
        // ایجاد المان جدید li
        const newElement = document.createElement('li');
        newElement.innerHTML = `
            <a href="/?loadbalance=true&action=form" >
                <span class="icon "></span>Load Balance
            </a>
        `;

        // اضافه کردن المان جدید بعد از المان هدف
        targetElement.parentNode.insertBefore(newElement, targetElement.nextSibling);
    } else {
        console.error('Element not found');
    }
    },5000)
});
    </script>