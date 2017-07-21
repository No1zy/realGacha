window.onload = function(){
    var parent = document.getElementById('images');
    var form = document.querySelector("form");
    parent.addEventListener('change',function(event){
        var element = event.target.previousSibling.parentElement.nextElementSibling;
        console.log(event.target.previousSibling.parentElement.nextElementSibling);
        if(element != null) return;
        var child_count = parent.childElementCount;

        /*
        '<div class="content' + child_count + '">';
            'カード名<input type="input" name="images_name' + child_count + '">';
            ' レアリティ<select name="rarity' + child_count + '">';
                 '<option value="1">ノーマル</option>';
                 '<option value="2">レア</option>';
                 '<option value="3">スーパーレア</option>';
                 '<option value="4">ウルトラレア</option>';
             '</select>';
        ' <input type="file" name="image' + child_count + '" value="">';
        */

        var contentDiv = document.createElement('div');
        contentDiv.setAttribute('class', "content" + child_count);
        contentDiv.innerText = "カード名";
        var card_name = document.createElement('input');
        card_name.setAttribute("name", "image_names[]");
        contentDiv.appendChild(card_name);
        contentDiv.innerHTML += " レアリティ";
        var select = document.createElement("select");
        select.setAttribute('name', 'rarities[]');

        for (i = 1 ; i < 4; ++i){
            var option = document.createElement("option");
            option.setAttribute("value", i);           
            switch(i){
                case 1:
                    option.innerHTML += "ノーマル";
                    break;
                case 2:
                    option.innerHTML += "レア";
                    break;
                case 3:
                    option.innerHTML += "スーパーレア";
                    break;
                case 4:
                    option.innerHTML += "ウルトラレア";
                    break;
            }
            select.appendChild(option);
        }

        contentDiv.appendChild(select);
        var inputFile = document.createElement("input");
        contentDiv.innerHTML += ' ';
        inputFile.setAttribute("type", "file");
        inputFile.setAttribute("name", "images[]");        
        contentDiv.appendChild(inputFile);
        parent.appendChild(contentDiv);
    },false);
    
    form.addEventListener('submit',function(event){
    
    },false);
}