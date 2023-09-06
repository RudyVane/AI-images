function filterImages() {
            var selectedTags = [];
            var checkboxes = document.querySelectorAll('input[name="tags[]"]:checked');

            checkboxes.forEach(function (checkbox) {
                selectedTags.push(checkbox.value);
            });

            var images = document.querySelectorAll('.image-item');

            images.forEach(function (image) {
                var tags = image.getAttribute('data-tags').split(',').map(tag => tag.trim()); // Remove spaces after commas
                var showImage = true;

                if (selectedTags.length === 0) {
                    // If no tags are checked, show all images
                    showImage = true;
                } else {
                    for (var i = 0; i < selectedTags.length; i++) {
                        if (tags.indexOf(selectedTags[i]) !== -1) {
                            // If the image has the selected tag
                            showImage = true;
                        } else {
                            showImage = false;
                            break;
                        }
                    }
                }

                if (showImage) {
                    image.style.display = 'block';
                } else {
                    image.style.display = 'none';
                }
            });
        }
		
        function clearCheckboxes() {
            var checkboxes = document.querySelectorAll('input[name="tags[]"]');
            checkboxes.forEach(function (item) {
                item.checked = false;
            });
            filterImages();
        }

        // Popup Functions
        function openPopup(imageSrc) {
            var popup = document.getElementById('imagePopup');
            var popupImg = document.getElementById('popupImg');
            popup.style.display = 'block';
            popupImg.src = imageSrc;
        }

        function closePopup() {
            var popup = document.getElementById('imagePopup');
            popup.style.display = 'none';
        }
		
		
