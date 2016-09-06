window.openImageWindow = (src, width, height) ->
  # open image in new window (window size = image size)
  window.open src, "Image", "width=#{width} +,height=#{height}"

window.removeImage = (coupon_id, image_id, url, width, height) ->
  $("#Image#{coupon_id}").replaceWith "<div id=\"Image#{coupon_id}\"><img class=\"couponImage\" src=\"#{url}?id=1\"><button type=\"button\" onclick=\"returnImage('#{coupon_id}', '#{image_id}', '#{url}', '#{width}', '#{height}');\">Return Image</button><input type=\"hidden\" class=\"form-control\" id=\"removeImage#{coupon_id}\" name=\"coupons[#{coupon_id}][removeImage]\"></div>"

window.clearImage = (coupon_id) ->
  $("#inputImage#{coupon_id}").val ''

window.returnImage = (coupon_id, image_id, url, width, height) ->
  $("#Image#{coupon_id}").replaceWith "<div id=\"Image#{coupon_id}\"><img class=\"couponImage\" src=\"#{url}?id=#{image_id}\" onclick=\"openImageWindow(this.src, '#{width}', '#{height}');\"><button type=\"button\" onclick=\"removeImage('#{coupon_id}', '#{image_id}', '#{url}', '#{width}', '#{height}');\">Remove</button></div>"

window.changeActivity = (coupon_id) ->
  # if coupon is active
  if $("#inputActivity#{coupon_id}").val() is '1'
    # set coupon activity into "deactivated"
    $("#inputActivity#{coupon_id}").val '0'
    # change link text to "Activate"
    $("#activationLink#{coupon_id}").text 'Activate'
    # setcoupon's class to change background color'
    $("#tr#{coupon_id}_1").attr 'class', 'tr1-body deactivated'
    $("#tr#{coupon_id}_2").attr 'class', 'tr2-body deactivated'
    return
  
  new_coupon = '';
  # if coupon is new set it's own class
  if coupon_id.indexOf('nc') > -1 
    new_coupon = ' newCoupon'

  # set coupon activity into "active"
  $("#inputActivity#{coupon_id}").val '1'
  # change link text to "Deactivate"
  $("#activationLink#{coupon_id}").text 'Deactivate'
  # setcoupon's class to change background color'
  $("#tr#{coupon_id}_1").attr 'class', "tr2-body#{new_coupon}"
  $("#tr#{coupon_id}_2").attr 'class', "tr2-body#{new_coupon}"

window.pageReset = ->
  document.location.reload()

window.removeCoupon = (coupon_id) ->
  # find coupon's position in the coupons table
  current_pos = + $("input[name='coupons[#{coupon_id}][position]']").val()
  #if current coupon is not new
  if coupon_id.indexOf('nc') is -1
    # add it's id into list of deleted coupons
    r_coupons = $("#rCoupons" ).val()
    if r_coupons is '' then r_coupons = coupon_id else r_coupons = "#{r_coupons},#{coupon_id}"
    $("#rCoupons").val r_coupons

  # remove rows with current coupon from table
  $("#tr#{coupon_id}_1").remove()
  $("#tr#{coupon_id}_2").remove()

  # set new position and # in the table for all coupons below
  for i in [current_pos * 2 - 1...$("#couponsTable tr").length] by 2
    $("#couponsTable tr:eq(#{i}) td:eq(1) input[class=inputPosition]").val (i + 1) / 2
    $("#couponsTable tr:eq(#{i}) td:eq(0)").text (i + 1) / 2


window.moveUp = (coupon_id) ->
  # find current coupon's position in the coupons table
  current_pos = + $("input[name='coupons[#{coupon_id}][position]']").val()
  # exit if current coupon is on the first position
  if current_pos is 1 
    return

  prev_pos = current_pos - 1
  # find id of coupon on the previous position in the coupons table
  prev_coupon_id = $("#couponsTable tr:eq(#{prev_pos * 2 - 1}) td:eq(1) input[class=inputPosition]").attr 'title'
  # move rows of previous coupon to position after current coupon
  $("#tr#{prev_coupon_id}_2").insertAfter $("#tr#{coupon_id}_2")
  $("#tr#{prev_coupon_id}_1").insertAfter $("#tr#{coupon_id}_2")
  # change var position for current and previous coupons
  $("#inputPosition#{coupon_id}").val prev_pos
  $("#inputPosition#{prev_coupon_id}").val current_pos
  # change # of coupon in the table
  $("#tr#{coupon_id}_1 td:eq(0)").text prev_pos
  $("#tr#{prev_coupon_id}_1 td:eq(0)").text current_pos

window.moveDown = (coupon_id) ->
  # find current coupon's position in the coupons table
  current_pos = + $("input[name='coupons[#{coupon_id}][position]']").val()
  # return if current coupon is on the last position
  if current_pos * 2 is $("#couponsTable tr").length - 1 
    return

  next_pos = current_pos + 1
  # find id of coupon on the next position in the coupons table
  next_coupon_id = $("#couponsTable tr:eq(#{next_pos * 2 - 1}) td:eq(1) input[class=inputPosition]").attr 'title'
  # move rows of current coupon to position after next coupon
  $("#tr#{coupon_id}_2").insertAfter $("#tr#{next_coupon_id}_2")
  $("#tr#{coupon_id}_1").insertAfter $("#tr#{next_coupon_id}_2")
  # change var position for current and previous coupons
  $("#inputPosition#{coupon_id}").val next_pos
  $("#inputPosition#{next_coupon_id}").val current_pos
  # change # of coupon in the table
  $("#tr#{coupon_id}_1 td:eq(0)").text next_pos
  $("#tr#{next_coupon_id}_1 td:eq(0)").text current_pos

window.makeFirst = (coupon_id) ->
  # find current coupon's position in the coupons table
  current_pos = + $("input[name='coupons[#{coupon_id}][position]']").val()
  # return if current coupon is on the first position
  if current_pos is 1
    return

  # find id of coupon on the first position in the coupons table
  first_coupon_id = $("#couponsTable tr:eq(1) td:eq(1) input[class=inputPosition]").attr 'title'
  # move rows of current coupon to position before first coupon
  $("#tr#{coupon_id}_1").insertBefore $("#tr#{first_coupon_id}_1")
  $("#tr#{coupon_id}_2").insertBefore $("#tr#{first_coupon_id}_1")

  # set new position and # in the table for all coupons above current coupon
  for i in [1..current_pos * 2 - 1] by 2
    $("#couponsTable tr:eq(#{i}) td:eq(1) input[class=inputPosition]").val (i + 1) / 2
    $("#couponsTable tr:eq(#{i}) td:eq(0)").text (i + 1) / 2

window.makeLast = (coupon_id) ->
  # find current coupon's position in the coupons table
  current_pos = + $("input[name='coupons[#{coupon_id}][position]']").val()
  # find last position in the coupons table
  last_row = $("#couponsTable tr").length - 1

  # return if current coupon is on the last position
  if current_pos * 2 is last_row
    return

  # find id of coupon on the last position in the coupons table
  last_coupon_id = $("#couponsTable tr:eq(#{last_row - 1}) td:eq(1) input[class=inputPosition]").attr 'title'
  # move rows of current coupon to position after last coupon
  $("#tr#{coupon_id}_2").insertAfter $("#tr#{last_coupon_id}_2")
  $("#tr#{coupon_id}_1").insertAfter $("#tr#{last_coupon_id}_2")

  # set new position and # in the table for all coupons below current coupon
  for i in [current_pos * 2 - 1...last_row] by 2
    $("#couponsTable tr:eq(#{i}) td:eq(1) input[class=inputPosition]").val (i + 1) / 2
    $("#couponsTable tr:eq(#{i}) td:eq(0)").text (i + 1) / 2


window.addCoupon = ->
  # remove text "No coupons found" if it exists
  if $("tr").is("#no_coupons")
    $("#no_coupons").remove()

  # count amount of new coupons
  window.nc_count++
  # find position for new coupon at the end of table
  position = ($("#couponsTable tr").length - 1) / 2 + 1
  # create id for new coupon
  coupon_id = "nc#{window.nc_count}"
  # create html-code of rows for new coupon
  new_row = "<tr class=\"tr1-body newCoupon\" id=\"tr#{coupon_id}_1\"> "
  new_row += "<td rowspan=\"2\">#{position}</td> "
  new_row += "<td colspan=\"4\"> "
  new_row += "<input type=\"hidden\" id=\"inputPosition#{coupon_id}\" class=\"inputPosition\" name=\"coupons[#{coupon_id}][position]\" title=\"#{coupon_id}\" value=\"#{position}\"> "
  new_row += "<input type=\"hidden\" id=\"inputActivity#{coupon_id}\" class=\"form-control\" name=\"coupons[#{coupon_id}][activity]\" value=\"1\"> "
  new_row += "<div> "
  new_row += "<textarea class=\"form-control\" name=\"coupons[#{coupon_id}][label]\" rows=\"3\"></textarea> "
  new_row += "<input type=\"hidden\" class=\"form-control\" name=\"coupons[#{coupon_id}][parent_id]\" value=\"\"> "
  new_row += "</div> "
  new_row += "</td> "
  new_row += "<td> "
  new_row += "<div> "
  new_row += "<input class=\"form-control\" type=\"text\" name=\"coupons[#{coupon_id}][code]\" value=\"\"> "
  new_row += "</div> "
  new_row += "</td> "
  new_row += "<td> "
  new_row += "<div> "
  new_row += "<input class=\"form-control\" type=\"text\" name=\"coupons[#{coupon_id}][link]\" value=\"\"> "
  new_row += "</div> "
  new_row += "</td> "
  new_row += "<td> "
  new_row += "Starts: "
  new_row += "<div> "
  new_row += "<input class=\"form-control\" type=\"text\" name=\"coupons[#{coupon_id}][startDate]\" value=\"\"> "
  new_row += "</div> "
  new_row += "Expires: "
  new_row += "<div> "
  new_row += "<input class=\"form-control\" type=\"text\" name=\"coupons[#{coupon_id}][expireDate]\" value=\"\"> "
  new_row += "</div> "
  new_row += "</td> "
  new_row += "<td> "
  new_row += "<a href=\"javascript:makeFirst('#{coupon_id};')\" class=\"couponActions\">First</a></br> "
  new_row += "<a href=\"javascript:makeLast('#{coupon_id};')\" class=\"couponActions\">Last</a></br> "
  new_row += "<a href=\"javascript:moveUp('#{coupon_id}');\" class=\"couponActions\">Up</a></br> "
  new_row += "<a href=\"javascript:moveDown('#{coupon_id}');\" class=\"couponActions\">Down</a></br> "
  new_row += "<a href=\"javascript:changeActivity('#{coupon_id}');\" class=\"couponActions\" id=\"activationLink#{coupon_id}\">Deactivate</a><br> "
  new_row += "<a href=\"javascript:removeCoupon('#{coupon_id}');\" class=\"couponActions\">Remove</a> "
  new_row += "</td> "
  new_row += "</tr> "
  new_row += "<tr class=\"tr2-body newCoupon\"  id=\"tr#{coupon_id}_2\"> "
  new_row += "<td> "
  new_row += "<div id=\"Image#{coupon_id}\"> "
  new_row += "<img class=\"couponImage\" src=\"#{window.url}?id=1\"> "
  new_row += "</div> "
  new_row += "</td> "
  new_row += "<td colspan=\"3\"> "
  new_row += "<div> "
  new_row += "<input type=\"file\" class=\"form-control\" id=\"inputImage#{coupon_id}\" placeholder=\"Choose image for coupon if needed\" name=\"newImage#{coupon_id}\" accept=\"#{window.accept_filter}\"> "
  new_row += "<button type=\"button\" onclick=\"clearImage('#{coupon_id}');\">Clear</button> "
  new_row += "</div> "
  new_row += "</td> "
  new_row += "<td colspan=\"5\"></td> "
  new_row += "</tr> ";
  # add new coupon at the end of table
  $("#couponsTable").append new_row