var mapLevelItem = '', mapSpriteItem = '';
var store = [];
var index = 0;

function upload() {
    $('.dashboard').css('display', 'none');
    $('.uploader').css('display', 'block');
}

function parse() {
    var world = $('input[name=world]').val();
    var level = $('input[name=level]').val();
    $('.uploader').html($('#template-preloader').html());
    $.post(
        "parser.php", {
            name: localStorage.getItem('file'),
            world: world,
            level: level
        }).done(function (res) {
        localStorage.setItem('level', JSON.stringify(res));
        $('.uploader').html($('#template-btnAfterParse').html());
        build(JSON.parse(localStorage.getItem('level')));
        getSprites(res.sources);
    });
}

function build(level) {
    var s = '<table><tr class="row">';
    level.map.forEach(function (obj, i) {
        var cntWidth = (level.info.width - 1) / 17;
        if (i % cntWidth == 0 && i > 0) {
            s += '</tr><tr class="tr-row">';
        }
        s += `<td class="map-img"><a data-item="${i}" onClick="getLevelItem(this)" style="background-position:-${(obj.col - 1) * 16 + obj.col}px -${(obj.row - 1) * 16 + obj.row}px; background-image:url('/${obj.src && level.sources[obj.src]}');"></a></td>`;
    });
    s += '</tr></table>';
    $('.level-sprite-container').css('display', 'block');
    $('.map-title').html('World ' + level.info.world + ' - Level ' + level.info.level);
    $('.level-sprite').html(s);
}

function getSprites(sources) {
    for (var key in sources) {
        buildSprite(sources[key], key);
    }
}

function buildSprite(spritePath, key) {
    var s = '<h3>' + spritePath + '</h3><table>', spriteWidth = 0, spriteHeight = 0;
    var sprite = new Image();
    sprite.src = spritePath;
    $(sprite).one('load', function () {
        spriteWidth = sprite.width;
        spriteHeight = sprite.height;
        var level = JSON.parse(localStorage.getItem('level'));
        var widthSpriteItem = level.sourcesSizes[key][0];
        var heightSpriteItem = level.sourcesSizes[key][1];
        var y = 0;
        for (var i = 0; i < (spriteHeight - 1) / (heightSpriteItem + 1); i++) {
            var x = 0;
            s += '<tr>';
            for (var j = 0; j < (spriteWidth - 1) / (widthSpriteItem + 1); j++) {
                s += `<td class="sprite-img" style="width: ${widthSpriteItem}px; height: ${heightSpriteItem}px;"><a data-source="${key}" data-row="${i + 1}" data-col="${j + 1}" onClick="getSpriteItem(this)" style="background-position: -${(x + 1)}px -${(y + 1)}px; background-image:url('${spritePath}'); width: ${widthSpriteItem}px; height: ${heightSpriteItem}px;"></a></td>`;
                x += widthSpriteItem + 1;
            }
            s += '</tr>';
            y += heightSpriteItem + 1;
        }
        s += '</table>';
        $('.sprites').append(s);
    });
}

function selectLevels() {
    $.post(
        "selectMaps.php", {}).done(function (levels) {
        renderLevelsList(levels);
    });
}

function renderLevelsList(levels) {
    $('.dashboard').css('display', 'none');
    $('.open-map-container').css('display', 'block');
    var s = '';
    levels.forEach(function (level, i) {
        s += '<div class="btn-level-container text-center col-xs-6 col-sm-6 col-md-3"><button type="button" class="btn btn-primary" onClick="openLevel(' + level.id + ')">Level ' + level.name + '</button> <button class="btn btn-danger" onClick="deleteLevel(' + level.id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button></div>'
    });
    $('.open-map').html(s);
}

function deleteLevel(id) {
    $.post(
        "deleteLevel.php", {
            id: id
        }).done(function () {
        selectLevels();
    });
}

function openLevel(id) {
    $('.open-map-container').css('display', 'none');
    $.post(
        "getLevel.php", {
            id: id
        }).done(function (res) {
        localStorage.setItem('level', res.level);
        store[index] = res.level;
        var level = JSON.parse(store[index])
        $('.uploader').css('display', 'block').html($('#template-btnAfterParse').html());
        build(level);
        checkBtn();
        getSprites(level.sources);
        $('input[name="levelId"]').val(id);
    });
}

function downloadLevel() {
    var level = localStorage.getItem('level');
    var levelObj = JSON.parse(level);
    download(level, 'level_' + levelObj.info.world + '_' + levelObj.info.level + '.json', 'txt');
}

// Function to download data to a file
function download(data, filename, type) {
    var a = document.createElement("a"),
        file = new Blob([data], {type: type});
    if (window.navigator.msSaveOrOpenBlob) // IE10+
        window.navigator.msSaveOrOpenBlob(file, filename);
    else { // Others
        var url = URL.createObjectURL(file);
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        setTimeout(function () {
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }, 0);
    }
}

function saveLevel() {
    var level = localStorage.getItem('level');
    var levelObj = JSON.parse(level);
    var name = levelObj.info.world + '_' + levelObj.info.level;
    $.post(
        "saveMap.php", {
            levelId: $('input[name="levelId"]').val(),
            name: name,
            level: level
        }).done(function () {
        bootbox.alert("Map successfully saved!");
    });
}

function getLevelItem(link) {
    var $link = $(link);
    $('.map-img').removeClass('img-get');
    $link.parent().addClass('img-get');
    mapLevelItem = $link.data('item');
    edit();
}

function getSpriteItem(link) {
    var $link = $(link);
    $('.sprite-img').removeClass('img-get');
    $link.parent().addClass('img-get');
    mapSpriteItem = {"src": $link.data('source'), "row": $link.data('row'), "col": $link.data('col')};
    edit();
}

function edit() {
    if ((mapLevelItem || mapLevelItem === 0) && mapSpriteItem) {
        var level = JSON.parse(store[index]);
        level.map[mapLevelItem] = mapSpriteItem;
        mapSpriteItem = '';
        index++;
        if (index !== store.length) {
            store.splice(index, store.length - index, JSON.stringify(level));
        } else {
            store[index] = JSON.stringify(level);
        }
        build(JSON.parse(store[index]));
        checkBtn();
        $('.map-img a[data-item="' + mapLevelItem + '"]').parent().addClass('img-get');
        localStorage.setItem('level', JSON.stringify(level));
    } else {
        return true;
    }
}

function undo() {
    if (index > 0) {
        index = index - 1;
        build(JSON.parse(store[index]));
        checkBtn();
    }
}

function redo() {
    if (index <= store.length - 1) {
        index = index + 1;
        build(JSON.parse(store[index]));
        checkBtn();
    }
}

function checkBtn() {
    var $btnUndo = $('button.btn-undo');
    var $btnRedo = $('button.btn-redo');
    console.log(index, store);
    if (index === 0) {
        $btnUndo.attr('disabled', true);
    } else {
        $btnUndo.attr('disabled', false);
    }
    if (index === store.length - 1) {
        $btnRedo.attr('disabled', true);
    } else {
        $btnRedo.attr('disabled', false);
    }
}