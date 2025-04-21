              

            </div>
        </div>
    </div>
    <div class="loader-container" style="display: none;">
        <div class="spinner-loader"></div>
    </div>
</body>
<script>
    // Display loader on form submission
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => {
            document.querySelector('.loader-container').style.display = '';
        });
    });

    // Display loader on link click
    document.querySelectorAll('a').forEach(link => {
        if (!link.getAttribute('href').startsWith('#')) {
            link.addEventListener('click', () => {
                document.querySelector('.loader-container').style.display = '';
            });
        }
    });
</script>

</html>
