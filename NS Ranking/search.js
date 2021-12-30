$( document ).ready(function() {
    var clan_id = getCookie("clanId");
    var newSearchBox = '<h4>Please enter clan id in the search box.</h4>\
    <div class="input-group mb-3">\
        <input type="text" id="clan_id" class="form-control" placeholder="Search">\
            <div class="input-group-append">\
                <button class="btn btn-success" id="go_btn" type="submit">Go</button>\
            </div></div>';

    if(clan_id != ""){
        console.log(clan_id);
        $.ajax({
            type:'POST',
            url:'searching.php',
            data:{clan: clan_id},
            success:function(data){
                var jsonArray = JSON.parse(data);
                if(jsonArray.name != null && jsonArray.id != null && jsonArray.members != null){
                    $("#searchBox").html(makeTableHTML(jsonArray.members));
                    $("#searchBox").prepend(putClanHeader(jsonArray.name));
                    $("#refresh").click(function(){
                        clickRefreshBtn(getCookie("clanId"));
                    });
                    $("#myInput").on("keyup", function() {
                        var value = $(this).val().toLowerCase();
                        $("#myTable tr").filter(function() {
                          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                        });
                    });
                }else{
                    alert("the clan id is not valid");
                    $("#searchBox").prepend(putClanHeader("Not Found"));
                    $("#searchBox").html(makeTableHTML(jsonArray.members));
                }
                console.log(jsonArray);

                $("#go_btn").click(function(){
                    clickGoBtn($("#clan_id").val());
                }); 
            },
            error:function(){
                alert('error loading');
            }
        });
        
    }else{
        $("#searchBox").html(newSearchBox);
        $("#go_btn").click(function(){
            var enteredId = $("#clan_id").val();
            $("#searchBox").html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            console.log(enteredId);
            $.ajax({
                type:'POST',
                url:'searching.php',
                data:{clan: enteredId},
                success:function(data){
                    var jsonArray = JSON.parse(data);
                    if(jsonArray.name != null && jsonArray.id != null && jsonArray.members != null){
                        $("#searchBox").html(makeTableHTML(jsonArray.members));
                        $("#searchBox").prepend(putClanHeader(jsonArray.name));
                        $("#myInput").on("keyup", function() {
                            var value = $(this).val().toLowerCase();
                            $("#myTable tr").filter(function() {
                            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                            });
                        });
                        $("#refresh").click(function(){
                            clickRefreshBtn(getCookie("clanId"));
                        });
                    }else{
                        $("#searchBox").html('<div class="clan_table"></div>');
                        $("#searchBox").prepend(putClanHeader(jsonArray.name));
                        alert("please enter a valid clan id");
                    }
                    $("#go_btn").click(function(){
                        clickGoBtn($("#clan_id").val());
                    }); 
                },
                error:function(){
                    alert('error loading');
                }
            });
        });
    }
});

//sort table
function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("membersTable");
    switching = true;
    //Set the sorting direction to ascending:
    dir = "asc"; 
    /*Make a loop that will continue until
    no switching has been done:*/
    while (switching) {
      //start by saying: no switching is done:
      switching = false;
      rows = table.rows;
      /*Loop through all table rows (except the
      first, which contains table headers):*/
      for (i = 1; i < (rows.length - 1); i++) {
        //start by saying there should be no switching:
        shouldSwitch = false;
        /*Get the two elements you want to compare,
        one from current row and one from the next:*/
        x = rows[i].getElementsByTagName("TD")[n];
        y = rows[i + 1].getElementsByTagName("TD")[n];
        /*check if the two rows should switch place,
        based on the direction, asc or desc:*/
        if (dir == "asc") {
          if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
            //if so, mark as a switch and break the loop:
            shouldSwitch= true;
            break;
          }
        } else if (dir == "desc") {
          if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
            //if so, mark as a switch and break the loop:
            shouldSwitch = true;
            break;
          }
        }
      }
      if (shouldSwitch) {
        /*If a switch has been marked, make the switch
        and mark that a switch has been done:*/
        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
        switching = true;
        //Each time a switch is done, increase this count by 1:
        switchcount ++;      
      } else {
        /*If no switching has been done AND the direction is "asc",
        set the direction to "desc" and run the while loop again.*/
        if (switchcount == 0 && dir == "asc") {
          dir = "desc";
          switching = true;
        }
      }
    }
  }

function clickGoBtn(save_id){
    $(".clan_table").html('<div class="spinner-border text-primary" role="status">\
    <span class="sr-only">Loading...</span>\
    </div>');
    console.log(save_id);
    $.ajax({
        type:'POST',
        url:'searching.php',
        data:{clan: save_id},
        success:function(data){
            console.log(data);
            var jsonArray = JSON.parse(data);
            if(jsonArray.name != null && jsonArray.id != null && jsonArray.members != null){
                console.log(jsonArray);
                $(".clan_table").html(makeTableHTML(jsonArray.members));
                $("div.clan_name h2").text(jsonArray.name);
                $("#myInput").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            }else{
                $(".clan_table").html('<div class="alert alert-danger">\
                <strong>Clan not found!</strong> Please enter a valid clan id.</div>');
                $("div.clan_name h2").text("Not Found");
            }
            
        },
        error:function(){
            alert('error loading');
        }
    });
}

function clickRefreshBtn(save_id){
    $(".clan-menu").append('<div class="spinner-border text-primary refreshing" role="status">\
    <span class="sr-only">Loading...</span>\
    </div>')
    console.log(save_id);
    $.ajax({
        type:'POST',
        url:'searching.php',
        data:{clan: save_id},
        success:function(data){
            $(".refreshing").remove();
            console.log(data);
            var jsonArray = JSON.parse(data);
            console.log(jsonArray);
            $(".clan_table").html(makeTableHTML(jsonArray.members));
            $("div.clan_name h2").text(jsonArray.name);
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });  
        },
        error:function(){
            $(".refreshing").remove();
            alert('error loading');
        }
    });
}

function putClanHeader(clanName){
    var clanHeader = '<div class="clearfix"><div class="float-left clan_name"><h2>'+clanName+'</h2></div> \
    <div class="float-right btn-group btn-group clan-menu"> \
        <a href="#searchClanBox" class="btn btn-info" id="change" type="button" data-toggle="collapse">Change clan</a>\
        <a href="#searchMember" class="btn btn-info" id="change" type="button" data-toggle="collapse">Filter Member</a>\
        <button class="btn btn-info" id="refresh" type="button">Refresh</a> \
    </div>\
    </div>\
    <div id="searchClanBox" class="collapse">\
        <div class="input-group mb-3">\
        <input type="text" id="clan_id" class="form-control" placeholder="Enter clan id">\
        <div class="input-group-append">\
            <button class="btn btn-success" id="go_btn" type="submit">Go</button>\
        </div></div>\
    </div>\
    <div id="searchMember" class="collapse">\
    <input class="form-control" id="myInput" type="text" placeholder="Search members"></div>';

    return clanHeader;
}

function makeTableHTML(clanData) {
    var result = "<div class='clan_table'><table class='table table-bordered' id='membersTable'>";
    // header row
    result += "<thead><tr>";
    result += "<th>Rank</th>";
    result += "<th>Name</th>";
    result += "<th>Level</th>";
    result += "<th>Reputation</th>";
    result += "</tr></thead><tbody id='myTable'>";
    $.each(clanData, function( i, member ) {
        result += "<tr>";
        result += "<td>" + member.Rank + "</td>";
        result += "<td>" + member.Name + "</td>";
        result += "<td>" + member.Level + "</td>";
        result += "<td>" + member.Reputation + "</td>";
        result += "</tr>";
    });
    result += "</tbody</table></div>";
    return result;
}

function setCookie(id){
    var d = new Date();
    d.setTime(d.getTime() + (10 * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = "clanId="+id+";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
        c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
        }
    }
    return "";
}