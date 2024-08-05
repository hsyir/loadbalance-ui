<script>

    
var loadbalanceIntervalId = null;
document.addEventListener('DOMContentLoaded', (event) => {

    loadbalanceIntervalId = setInterval(insertLoadbalanceMenu, 1000);

});
function insertLoadbalanceMenu(){

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
        
        clearInterval(loadbalanceIntervalId);

    } else {
        console.error('Element not found');
    }
}
    </script>