              

            </div>
        </div>
    </div>
    <div class="loader-container" style="display: none;">
        <div class="spinner-loader"></div>
    </div>
</body>
<script>
    //loader effect
    function displayLoader() {
        document.querySelector('.loader-container').style.display = '';
        //onclick="displayLoader()"
    }
    
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', displayLoader);
    });

    document.querySelectorAll('a').forEach(link => {
        if (!link.getAttribute('href').startsWith('#')) {
            link.addEventListener('click', displayLoader);
        }
    });
</script>

</html>
