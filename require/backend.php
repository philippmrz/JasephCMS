<?php
  // PHP file for all backend logic, includes a class for all things DatabaseConnection

  // Establishes a database connection
  $db = new DatabaseConnection();
  // Automatically checks for authentication status and deletes cookies if not authenticated
  if (!$db->auth()) {
    $db->deleteAuthCookies();
  }

  function invertSortOrder() {
    return (isset($_GET['sort']) and $_GET['sort'] == 'ASC') ? "DESC" : "ASC";
  }

  // Returns SVG with name $svg from $svg_list
  function getSVG($svg) {
    $svg_list = [
      'sort' => 'M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z',
      'home' => 'M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z',
      'newpost' => 'M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z',
      'saved' => 'M17 3H7c-1.1 0-1.99.9-1.99 2L5 21l7-3 7 3V5c0-1.1-.9-2-2-2z',
      'saved-check' => 'M17,3A2,2 0 0,1 19,5V21L12,18L5,21V5C5,3.89 5.9,3 7,3H17M11,14L17.25,7.76L15.84,6.34L11,11.18L8.41,8.59L7,10L11,14Z',
      'saved-add' => 'M17,3A2,2 0 0,1 19,5V21L12,18L5,21V5C5,3.89 5.9,3 7,3H17M11,7V9H9V11H11V13H13V11H15V9H13V7H11Z',
      'saved-remove' => 'M17,3A2,2 0 0,1 19,5V21L12,18L5,21V5C5,3.89 5.9,3 7,3H17M8.17,8.58L10.59,11L8.17,13.41L9.59,14.83L12,12.41L14.41,14.83L15.83,13.41L13.41,11L15.83,8.58L14.41,7.17L12,9.58L9.59,7.17L8.17,8.58Z',
      'profile' => 'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z',
      'users' => 'M7.45,8,7.36,6.71a5.18,5.18,0,0,1,2.22-4A5.29,5.29,0,0,1,11.08,2a2.72,2.72,0,0,0-.84-.85A3.9,3.9,0,0,0,6.35.9a3.83,3.83,0,0,0-.76,6.3A3.46,3.46,0,0,0,7.45,8Zm6.47-6a5.93,5.93,0,0,1,1.19.51,4.61,4.61,0,0,1,1,.78,4.71,4.71,0,0,1,1.4,2.43,4.4,4.4,0,0,1,.14,1v.62L17.55,8a3.46,3.46,0,0,0,1.86-.83A3.84,3.84,0,0,0,18.7.92a3.94,3.94,0,0,0-3.81.15A3,3,0,0,0,13.92,2ZM12,3.18a4.17,4.17,0,0,0-1.19.35A3.82,3.82,0,0,0,8.69,6.88,3.77,3.77,0,0,0,11,10.44a3.52,3.52,0,0,0,1.24.3,3.7,3.7,0,0,0,2.35-.56,3.59,3.59,0,0,0,1-.95,3.84,3.84,0,0,0-1.52-5.76A4.07,4.07,0,0,0,12,3.18ZM.5,15.65H3.69a3.52,3.52,0,0,1,1.11-1.9,7.78,7.78,0,0,1,3.4-1.89l1-.26.71-.16-.88-.67a4.27,4.27,0,0,1-.47-.48c-.09-.1-.2-.27-.33-.32a2.29,2.29,0,0,0-.53,0l-.45,0H6.83a10.8,10.8,0,0,0-4.74,1.41A2.94,2.94,0,0,0,.5,13.75Zm14.57-4.21.71.16,1,.26a7.78,7.78,0,0,1,3.4,1.89,3.52,3.52,0,0,1,1.11,1.9H24.5v-1.9a2.94,2.94,0,0,0-1.59-2.36A10.8,10.8,0,0,0,18.17,10h-.44l-.45,0a2.29,2.29,0,0,0-.53,0c-.11,0-.63.65-.8.8ZM4.93,18.31H20.07V16.4a2.63,2.63,0,0,0-1.15-2A9,9,0,0,0,15,12.81a13.74,13.74,0,0,0-7.84.88c-1.06.53-2.21,1.42-2.21,2.71Z',
      'friends' => 'M16,13C15.71,13 15.38,13 15.03,13.05C16.19,13.89 17,15 17,16.5V19H23V16.5C23,14.17 18.33,13 16,13M8,13C5.67,13 1,14.17 1,16.5V19H15V16.5C15,14.17 10.33,13 8,13M8,11A3,3 0 0,0 11,8A3,3 0 0,0 8,5A3,3 0 0,0 5,8A3,3 0 0,0 8,11M16,11A3,3 0 0,0 19,8A3,3 0 0,0 16,5A3,3 0 0,0 13,8A3,3 0 0,0 16,11Z',
      'settings' => 'M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z',
      'login' => 'M7,14A2,2 0 0,1 5,12A2,2 0 0,1 7,10A2,2 0 0,1 9,12A2,2 0 0,1 7,14M12.65,10C11.83,7.67 9.61,6 7,6A6,6 0 0,0 1,12A6,6 0 0,0 7,18C9.61,18 11.83,16.33 12.65,14H17V18H21V14H23V10H12.65Z',
      'register' => 'M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z',
      'drafts' => 'M12,2A7,7 0 0,0 5,9C5,11.38 6.19,13.47 8,14.74V17A1,1 0 0,0 9,18H15A1,1 0 0,0 16,17V14.74C17.81,13.47 19,11.38 19,9A7,7 0 0,0 12,2M9,21A1,1 0 0,0 10,22H14A1,1 0 0,0 15,21V20H9V21Z',
      'savedraft' => 'M16.5,6V17.5A4,4 0 0,1 12.5,21.5A4,4 0 0,1 8.5,17.5V5A2.5,2.5 0 0,1 11,2.5A2.5,2.5 0 0,1 13.5,5V15.5A1,1 0 0,1 12.5,16.5A1,1 0 0,1 11.5,15.5V6H10V15.5A2.5,2.5 0 0,0 12.5,18A2.5,2.5 0 0,0 15,15.5V5A4,4 0 0,0 11,1A4,4 0 0,0 7,5V17.5A5.5,5.5 0 0,0 12.5,23A5.5,5.5 0 0,0 18,17.5V6H16.5Z',
      'expand-vertical' => 'M12,16A2,2 0 0,1 14,18A2,2 0 0,1 12,20A2,2 0 0,1 10,18A2,2 0 0,1 12,16M12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12A2,2 0 0,1 12,10M12,4A2,2 0 0,1 14,6A2,2 0 0,1 12,8A2,2 0 0,1 10,6A2,2 0 0,1 12,4Z',
      'confirm' => 'M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z',
      'delete' => 'M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z',
      'jaseph' => 'M 539.00,34.00 C 539.00,34.00 539.00,141.00 539.00,141.00 539.00,141.00 551.00,141.00 551.00,141.00 551.00,141.00 551.00,96.00 551.00,96.00 551.06,91.39 551.51,85.80 554.43,82.04 561.51,72.93 576.60,72.68 582.64,83.01 585.31,87.60 585.97,95.70 586.00,101.00 586.00,101.00 586.00,141.00 586.00,141.00 586.00,141.00 598.00,141.00 598.00,141.00 598.00,141.00 598.00,99.00 598.00,99.00 598.00,90.76 598.74,82.47 594.47,75.00 590.09,67.32 583.58,64.11 575.00,64.00 564.58,63.88 556.32,65.01 552.00,76.00 550.09,69.28 551.00,42.75 551.00,34.00 551.00,34.00 539.00,34.00 539.00,34.00 Z M 155.00,40.00 C 155.00,40.00 155.00,51.00 155.00,51.00 155.00,51.00 190.00,51.00 190.00,51.00 190.00,51.00 190.00,103.00 190.00,103.00 189.99,107.16 189.28,117.35 187.85,121.00 182.16,135.54 161.57,135.23 157.00,117.00 157.00,117.00 145.00,119.00 145.00,119.00 145.73,133.85 156.50,142.83 171.00,143.00 182.36,143.13 192.26,141.95 198.64,131.00 204.15,121.54 203.00,109.56 203.00,99.00 203.00,99.00 203.00,40.00 203.00,40.00 203.00,40.00 155.00,40.00 155.00,40.00 Z M 268.00,98.00 C 268.00,98.00 251.00,98.00 251.00,98.00 245.05,98.01 240.71,98.43 235.00,100.36 231.00,101.71 227.26,103.66 224.65,107.09 219.24,114.19 219.91,128.09 225.39,134.98 230.76,141.73 236.87,142.96 245.00,143.00 249.55,143.02 253.77,143.36 258.00,141.33 263.05,138.90 265.48,135.85 268.00,131.00 273.66,142.89 276.90,141.00 289.00,141.00 289.00,141.00 289.00,130.00 289.00,130.00 289.00,130.00 280.00,130.00 280.00,130.00 280.00,130.00 280.00,90.00 280.00,90.00 279.96,81.60 278.69,75.97 271.98,70.22 263.17,62.67 245.06,62.15 235.00,67.40 231.45,69.25 224.46,74.43 225.01,79.00 225.34,81.71 228.90,85.46 231.83,84.66 233.44,84.23 236.42,80.79 238.04,79.55 240.77,77.46 244.60,75.86 248.00,75.33 255.24,74.19 263.49,77.95 266.27,85.00 267.33,87.70 267.93,94.94 268.00,98.00 Z M 345.00,84.00 C 345.00,84.00 354.00,77.00 354.00,77.00 349.12,67.99 339.91,64.12 330.00,64.00 324.57,63.94 319.20,63.69 314.00,65.55 302.30,69.73 297.66,81.36 300.71,93.00 304.73,108.35 323.68,107.77 332.00,110.17 337.79,111.84 343.70,116.14 342.11,123.00 339.30,135.09 323.17,133.49 315.00,128.65 312.50,127.17 307.79,122.78 306.00,122.33 303.31,121.66 299.12,124.87 298.17,127.29 295.85,133.14 309.59,140.20 314.00,141.64 317.64,142.83 319.23,142.95 323.00,142.77 326.61,143.04 332.62,143.17 336.00,142.77 348.81,140.12 355.52,130.75 354.96,118.00 354.16,99.82 338.63,99.27 325.00,96.54 319.27,95.39 310.96,91.16 312.47,84.00 314.67,73.61 328.30,73.27 336.00,76.92 339.79,78.72 342.06,81.13 345.00,84.00 Z M 434.00,125.00 C 434.00,125.00 424.00,119.00 424.00,119.00 416.18,132.38 399.44,137.72 388.33,124.96 383.13,118.99 383.31,114.26 382.00,107.00 382.00,107.00 436.00,107.00 436.00,107.00 436.00,94.43 436.30,82.95 426.90,73.04 415.43,60.95 392.08,60.49 380.17,72.18 372.17,80.05 368.61,94.01 369.04,105.00 369.46,115.83 372.74,128.24 381.09,135.67 386.83,140.78 393.45,142.91 401.00,143.00 416.55,143.18 427.11,140.43 434.00,125.00 Z M 468.00,76.00 C 468.00,76.00 467.00,66.00 467.00,66.00 467.00,66.00 455.00,66.00 455.00,66.00 455.00,66.00 455.00,170.00 455.00,170.00 455.00,170.00 467.00,170.00 467.00,170.00 467.00,170.00 467.00,130.00 467.00,130.00 470.25,135.20 473.15,139.11 479.00,141.64 481.96,142.91 483.86,142.95 487.00,143.00 497.51,143.14 505.40,142.25 512.21,132.99 522.83,118.54 522.84,89.79 512.90,75.00 507.91,67.60 500.77,64.11 492.00,64.00 481.29,63.87 472.52,64.49 468.00,76.00 Z M 423.00,97.00 C 423.00,97.00 383.00,97.00 383.00,97.00 383.06,90.57 383.69,86.07 388.21,81.04 396.79,71.50 412.06,72.23 419.30,83.00 421.99,87.01 422.78,92.28 423.00,97.00 Z M 483.00,75.30 C 498.83,73.77 506.82,84.01 507.00,99.00 507.16,112.60 507.16,128.67 490.00,131.67 486.48,132.29 482.40,132.01 479.00,130.95 468.27,127.61 467.05,120.01 467.00,110.00 466.93,94.78 463.62,79.09 483.00,75.30 Z M 268.00,107.00 C 268.00,111.68 268.35,117.62 266.74,122.00 262.82,132.62 244.03,136.84 236.51,127.79 231.89,122.23 232.32,110.82 245.00,108.02 249.53,107.01 251.46,107.01 256.00,107.00 256.00,107.00 268.00,107.00 268.00,107.00 Z',
      'logo-inner' => 'M 21.00,42.00 C 21.00,42.00 21.00,52.00 21.00,52.00 21.00,52.00 101.00,52.00 101.00,52.00 101.00,52.00 101.00,42.00 101.00,42.00 101.00,42.00 21.00,42.00 21.00,42.00 Z M 21.00,72.00 C 21.00,72.00 21.00,82.00 21.00,82.00 21.00,82.00 101.00,82.00 101.00,82.00 101.00,82.00 101.00,72.00 101.00,72.00 101.00,72.00 21.00,72.00 21.00,72.00 Z M 21.00,102.00 C 21.00,102.00 21.00,112.00 21.00,112.00 21.00,112.00 101.00,112.00 101.00,112.00 101.00,112.00 101.00,102.00 101.00,102.00 101.00,102.00 21.00,102.00 21.00,102.00 Z M 21.00,132.00 C 21.00,132.00 21.00,142.00 21.00,142.00 21.00,142.00 101.00,142.00 101.00,142.00 101.00,142.00 101.00,132.00 101.00,132.00 101.00,132.00 21.00,132.00 21.00,132.00 Z',
      'logo-outer' => 'M 7.13,24.57 C 2.69,26.76 3.06,29.42 3.00,34.00 3.00,34.00 3.00,55.00 3.00,55.00 3.00,55.00 3.00,148.00 3.00,148.00 3.00,150.65 2.78,155.29 4.31,157.49 6.14,160.12 10.16,159.96 13.00,160.00 13.00,160.00 29.00,160.00 29.00,160.00 29.00,160.00 107.00,160.00 107.00,160.00 109.90,160.00 114.97,160.32 117.15,158.15 119.05,156.25 118.97,152.51 119.00,150.00 119.00,150.00 119.00,130.00 119.00,130.00 119.00,130.00 119.00,36.00 119.00,36.00 119.00,33.35 119.22,28.71 117.69,26.51 115.86,23.88 111.84,24.04 109.00,24.00 109.00,24.00 93.00,24.00 93.00,24.00 93.00,24.00 37.00,24.00 37.00,24.00 37.00,24.00 7.13,24.57 7.13,24.57 Z M 111.00,32.00 C 111.00,32.00 111.00,152.00 111.00,152.00 111.00,152.00 11.00,152.00 11.00,152.00 11.00,152.00 11.00,32.00 11.00,32.00 11.00,32.00 111.00,32.00 111.00,32.00 Z',
      'mask' => 'M 40.57,23.00 C 40.57,23.00 51.17,33.91 51.17,33.91 51.17,33.91 66.96,33.96 66.96,33.96 66.96,33.96 80.99,16.48 80.99,16.48 80.99,16.48 73.00,0.98 73.00,0.98 73.00,0.98 40.48,5.02 40.48,5.02 40.48,5.02 8.02,1.00 8.02,1.00 8.02,1.00 0.04,16.48 0.04,16.48 0.04,16.48 14.13,33.91 14.13,33.91 14.13,33.91 29.96,33.91 29.96,33.91 29.96,33.91 40.57,23.00 40.57,23.00 Z M 30.00,20.02 C 30.00,20.02 16.00,23.00 16.00,23.00 16.00,23.00 13.00,10.00 13.00,10.00 13.00,10.00 31.00,13.00 31.00,13.00 31.00,13.00 30.00,20.02 30.00,20.02 Z M 50.02,13.02 C 50.02,13.02 68.00,10.00 68.00,10.00 68.00,10.00 65.00,23.00 65.00,23.00 65.00,23.00 51.00,19.96 51.00,19.96 51.00,19.96 50.02,13.02 50.02,13.02 Z',
      'palette' => 'M17.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,9A1.5,1.5 0 0,1 19,10.5A1.5,1.5 0 0,1 17.5,12M14.5,8A1.5,1.5 0 0,1 13,6.5A1.5,1.5 0 0,1 14.5,5A1.5,1.5 0 0,1 16,6.5A1.5,1.5 0 0,1 14.5,8M9.5,8A1.5,1.5 0 0,1 8,6.5A1.5,1.5 0 0,1 9.5,5A1.5,1.5 0 0,1 11,6.5A1.5,1.5 0 0,1 9.5,8M6.5,12A1.5,1.5 0 0,1 5,10.5A1.5,1.5 0 0,1 6.5,9A1.5,1.5 0 0,1 8,10.5A1.5,1.5 0 0,1 6.5,12M12,3A9,9 0 0,0 3,12A9,9 0 0,0 12,21A1.5,1.5 0 0,0 13.5,19.5C13.5,19.11 13.35,18.76 13.11,18.5C12.88,18.23 12.73,17.88 12.73,17.5A1.5,1.5 0 0,1 14.23,16H16A5,5 0 0,0 21,11C21,6.58 16.97,3 12,3Z'
    ];
    return $svg_list[$svg];
  }

  // Returns the sort SVG to be used for sort floating action button
  function getSortSVG() {
    $rotate = (isset($_GET['sort']) and $_GET['sort'] == 'ASC') ? 'true' : 'false';
    $path = getSVG('sort');
    $svg = "<svg rotate='$rotate' xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path d='$path'/></svg>";
    return $svg;
  }

  // Returns a random string (0-9;a-Z;!;$;.) with length $length
  function randomString($length) {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!$.";
    $c_length = strlen($characters);
    $string = "";
    for ($i = 0; $i < $length; $i++) {
      $string .= $characters[rand(0, $c_length - 1)];
    }
    return $string;
  }

 // Subtracts a date from the current time and returns string: 'x (unit[s]) ago'
 // Example: '5 minutes ago'
  function convertDate($date) {
    $from = new DateTime($date);
    $diff = $from->diff(new DateTime(date('Y-m-d H:i:s')));
    return ($diff->y == 0 ? ($diff->m == 0 ? ($diff->d == 0 ? ($diff->h == 0 ? ($diff->i == 0 ? 'under a minute' : $diff->i . ' ' . ($diff->i == 1 ? 'minute' : 'minutes')) : $diff->h . ' ' . ($diff->h == 1 ? 'hour' : 'hours')) : $diff->d . ' ' . ($diff->d == 1 ? 'day' : 'days')) : $diff->m . ' ' . ($diff->m == 1 ? 'month' : 'months')) : $diff->y . ' ' . ($diff->y == 1 ? 'year' : 'years')) . ' ago';
  }

  // Instantiate with $var = new DatabaseConnection
  // No need to create a new mysqli connection, everything is handled from the class
  // Just move your function to this class and call it with $var->yourFunction();
  class DatabaseConnection extends mysqli {

    // Path to avatar and temp avatar directories
    const AVATAR_DIRECTORY = "assets/avatar";
    const TEMP_AVATAR_DIRECTORY = "assets/avatar/temp";

    // Constructor, this gets called every time a new instance of DatabaseConnection is created
    function __construct() {
      require('credentials.php');
      $instance = @parent::__construct($db_servername, $db_username, $db_password, $db_name);

      if ($this->connect_error) {
        die($this->connect_errno . $this->connect_error);
      }

      // Creates directory for avatar storage
      if (!is_dir(self::AVATAR_DIRECTORY)) {
        mkdir(self::AVATAR_DIRECTORY, 0777, true);
      }

      // Creates directory for temp avatar storage
      if (!is_dir(self::TEMP_AVATAR_DIRECTORY)) {
        mkdir(self::TEMP_AVATAR_DIRECTORY, 0777, true);
      }
    }

    function getActive($link) {
      if ($link == 'index') {
        return (strpos($_SERVER['REQUEST_URI'], '/index') !== false or $_SERVER['REQUEST_URI'] == '' or $_SERVER['REQUEST_URI'] == '/');
      } elseif ($link == 'myprofile') {
        $userid = self::getCurUser();
        return (strpos($_SERVER['REQUEST_URI'], "/profile?id=$userid") !== false);
      } else {
        return (strpos($_SERVER['REQUEST_URI'], "/$link") !== false);
      }
    }

    function getVisibility() {
      require('credentials.php');
      $userid = self::getCurUser();
      $getVisibility = @parent::query("SELECT VISIBILITY FROM $usertable WHERE USERID = $userid");
      $row = $getVisibility->fetch_assoc();
      if ($row['VISIBILITY'] == 'VISIBLE') {
        return true;
      } else {
        return false;
      }
    }

    function getTempImgPath() {
      require('credentials.php');
      $userID = self::getCurUser();
      $getTempImgPath = @parent::query("SELECT TEMP_PATH FROM $imgtable WHERE USERID = '$userID'");
      $row = $getTempImgPath->fetch_assoc();
      return $row['TEMP_PATH'];
    }

    function getImgPath($userID) {
      require('credentials.php');
      $getImgPath = @parent::query("SELECT PATH FROM $imgtable WHERE USERID = '$userID'");
      $row = $getImgPath->fetch_assoc();
      if (!is_null($row['PATH'])) {
        return $row['PATH'];
      } else {
        return 'assets/default-avatar.png';
      }
    }

    function checkImg() {
      $msg = [];
      if (!isset($_FILES["picFile"]) or!file_exists($_FILES["picFile"]["tmp_name"])) {
        array_push($msg, 'No image selected.');
      }
      $file = $_FILES["picFile"]["tmp_name"];
      $imgsize = getimagesize($file);
      $width = $imgsize[0];
      $height = $imgsize[1];
      if ($width / $height != 1) {
        array_push($msg, 'Image must have ratio of 1:1.');
      }
      return $msg;
    }

    function createImgPath() {
      require('credentials.php');
      $filename = $_FILES["picFile"]["name"];
      $extension = pathinfo($filename, PATHINFO_EXTENSION);
      $userID = self::getCurUser();
      $tempPathTarget = self::TEMP_AVATAR_DIRECTORY . '/av_' . $userID . '.' . $extension;
      $pathTarget = self::AVATAR_DIRECTORY . '/av_' . $userID . '.' . $extension;
      $checkRows = @parent::query("SELECT * FROM $imgtable WHERE USERID = '$userID'");
      if (!$checkRows or $checkRows->num_rows == 0) {
        $result = @parent::query("INSERT INTO $imgtable (USERID) VALUES ('$userID')");
        self::createImgPath();
      } else {
        $r = @parent::query("SELECT TEMP_PATH FROM $imgtable WHERE TEMP_PATH = '$tempPathTarget' AND USERID = '$userID'");
        if ($r->num_rows > 0) {
          unlink($tempPathTarget);
          $doubleImg = true;
        } else {
          $doubleImg = false;
        }
        if (move_uploaded_file($_FILES["picFile"]["tmp_name"], $tempPathTarget)) {
          if (!is_null(self::getTempImgPath()) && !$doubleImg){
            unlink(self::getTempImgPath());
          }
          $movetoTemp = @parent::query("UPDATE $imgtable SET TEMP_PATH = '$tempPathTarget' WHERE USERID = '$userID'");
        }
      }
    }

    function createPost($userid, $title, $content) {
      require('credentials.php');
      return @parent::query("INSERT INTO $posttable (USERID, TITLE, CONTENT) VALUES ('$userid', '$title', '$content')");
    }

    function createDraft($userid, $title, $content) {
      require('credentials.php');
      return @parent::query("INSERT INTO $drafttable (USERID, TITLE, CONTENT) VALUES ('$userid', '$title', '$content')");
    }

    function addToSavedPosts($postid) {
      require('credentials.php');
      $userid = self::getCurUser();
      $getSaved = @parent::query("SELECT POSTID FROM saved WHERE POSTID = '$postid' AND USERID = '$userid'");
      if ($getSaved->num_rows == 0) {
        @parent::query("INSERT INTO saved (USERID,POSTID) VALUES ($userid,'$postid')");
      }
    }

    function removeSavedPost($postid, $userid = NULL) {
      require('credentials.php');
      if (is_null($userid)) {
        $userid = self::getCurUser();
      }
      return @parent::query("DELETE FROM saved WHERE POSTID = '$postid' AND USERID = '$userid'");
    }

    function postsAusgeben($order) {
      require('credentials.php');

      $order = ($order == 'ASC') ? 'ASC' : 'DESC';

      if (basename($_SERVER['PHP_SELF']) == "myposts.php" or basename($_SERVER['PHP_SELF']) == "profile.php") {
        $userID = self::getCurUser();
        $sqlQuery = "SELECT POSTID, substring(TITLE, 1, 50) AS TITLE, substring(CONTENT, 1, 200) AS CONTENT, DATE, substring(DATE, 1, 10) AS DAY, substring(DATE, 12, 5) AS TIME, U.USERID AS USERID, USERNAME, VISIBILITY from $posttable P, $usertable U WHERE P.USERID = U.USERID AND P.USERID = $userID ORDER BY DATE $order";
      } else if (basename($_SERVER['PHP_SELF']) == "saved.php") {
        $userID = self::getCurUser();
        $sqlQuery = "SELECT P.POSTID, substring(TITLE, 1, 50) AS TITLE, substring(CONTENT, 1, 200) AS CONTENT, DATE, substring(DATE, 1, 10) AS DAY, substring(DATE, 12, 5) AS TIME, U.USERID, USERNAME, VISIBILITY from $posttable P, $usertable U, saved S WHERE P.USERID = U.USERID AND P.POSTID=S.POSTID AND S.USERID=$userID ORDER BY DATE ASC";
      } else {
        $sqlQuery = "SELECT POSTID, substring(TITLE, 1, 50) AS TITLE, CONTENT, DATE, U.USERID AS USERID, USERNAME, VISIBILITY from $posttable P, $usertable U WHERE U.USERID = P.USERID ORDER BY DATE $order";
      }
      $r = @parent::query($sqlQuery);
      $return = "";
      if (!$r) {
        return 'No posts saved yet.';
      }
      while ($row = $r->fetch_assoc()){
        if ($row['VISIBILITY'] == 'VISIBLE' or self::checkSelf($row['USERID']) or self::getRole(self::getCurUser()) == 'ADMIN') {
          if ($row['VISIBILITY'] == 'VISIBLE') {
            $uname = $row['USERNAME'];
          } else {
            $uname = $row['USERNAME'] . "<span class='anon'>(anonymous)</span>";
          }
          $img = self::getImgPath($row['USERID']);
        } else {
          $uname = 'Anonymous';
          $img = 'assets/default-avatar.png';
        }
        $date = convertDate($row['DATE']);
        $title = htmlspecialchars($row['TITLE']);
        $content = htmlspecialchars($row['CONTENT']);
        $return .= <<<MYSQL
        <a class='post' href='onepost.php?id=$row[POSTID]'>
            <img class='thumbnail' src='$img'>

            <div class='post-without-tn'>
              <div class='post-info'>
                <p class='title'>$title</p>
                <div class='date-uname'>
                  <p class='username'>
                    $uname
                  </p>
                  <p class='date'>
                    $date
                  </p>
                </div>
              </div>
              <span class='post-text md'>$content</span>
            </div>
        </a>
        <hr>
MYSQL;
      }
      return $return;
    }

    function einenPostAusgeben($id) {
      require('credentials.php');

      $r = @parent::query("SELECT TITLE, CONTENT, substring(DATE, 1, 10) AS DAY, substring(DATE, 12, 5) AS TIME, USERNAME, U.USERID AS USERID, VISIBILITY from $posttable P, $usertable U WHERE P.USERID = U.USERID AND POSTID = $id");

      $row = $r->fetch_assoc();
      if ($row['VISIBILITY'] == 'VISIBLE' or self::checkSelf($row['USERID']) or self::getRole(self::getCurUser()) == 'ADMIN') {
        if ($row['VISIBILITY'] == 'VISIBLE') {
          $uname = "<a href='profile?id=$row[USERID]'>$row[USERNAME]</a>";
        } else {
          $uname = "<a href='profile?id=$row[USERID]'>$row[USERNAME] <span class='anon'>(anonymous)</span></a>";
        }
      } else {
        $uname = 'Anonymous';
      }
      $title = htmlspecialchars($row['TITLE']);
      $content = htmlspecialchars($row['CONTENT']);
      return <<<RETURN
      <div id='post'>
        <p id='title'>$title</p>
        <div id='post-info'>
          <p id='username-top'>
            posted by $uname
          </p>
          <p id='date'>
            on $row[DAY] at $row[TIME]
          </p>
        </div>
        <span id='post-text' class='md'>$content</span>
      </div>
RETURN;
    }

    function getSaved($postid, $userid = NULL) {
      require('credentials.php');
      if (is_null($userid)) {
        $userid = self::getCurUser();
      }
      if ($getSaved = @parent::query("SELECT POSTID, USERID FROM saved WHERE POSTID = $postid AND USERID = $userid")->num_rows > 0) {
        return true;
      } else {
        return false;
      }
    }

    function draftsAusgeben() {
      require('credentials.php');
      $userid = self::getCurUser();
      $sqlQuery = "SELECT DRAFTID, substring(TITLE, 1, 50) AS TITLE, CONTENT, U.USERID AS USERID, USERNAME from $drafttable D, $usertable U WHERE U.USERID = D.USERID AND U.USERID = '$userid'";
      $r = @parent::query($sqlQuery);
      $return = "";
      if (!$r) {
        return 'No drafts saved yet.';
      }
      while ($row = $r->fetch_assoc()){
        $img = self::getImgPath($row['USERID']);
        $uname = $row['USERNAME'];
        $title = htmlspecialchars($row['TITLE']);
        $content = htmlspecialchars($row['CONTENT']);
        $return .= <<<MYSQL
        <a class='post' href='onedraft.php?id=$row[DRAFTID]'>
            <img class='thumbnail' src='$img'>

            <div class='post-without-tn'>
              <div class='post-info'>
                <p class='title'>$title</p>
                <div class='date-uname'>
                  <p class='username'>
                    $uname
                  </p>
                </div>
              </div>
              <span class='post-text md'>$content</span>
            </div>
        </a>
        <hr>
MYSQL;
      }
      return $return;
    }

    function einenDraftAusgeben($id) {
      require('credentials.php');

      $r = @parent::query("SELECT TITLE, CONTENT, USERNAME, U.USERID AS USERID from $drafttable D, $usertable U WHERE D.USERID = U.USERID AND DRAFTID = $id");

      $row = $r->fetch_assoc();
      $uname = $row['USERNAME'];
      $title = htmlspecialchars($row['TITLE']);
      $content = htmlspecialchars($row['CONTENT']);
      return <<<RETURN
      <div id='post'>
        <p id='title'>$title</p>
        <div id='post-info'>
          <p id='username-top'>
            draft by $uname
          </p>
        </div>
        <span id='post-text' class='md'>$content</span>
      </div>
RETURN;
    }

    function getCurUser() {
      require('credentials.php');
      if (isset($_COOKIE['identifier'])) {
        return self::getUserID($_COOKIE['identifier']);
      } else {
        return false;
      }
    }

    function getRole($userid) {
      require('credentials.php');
      return @parent::query("SELECT ROLE FROM $usertable WHERE USERID = '$userid'")->fetch_assoc()['ROLE'];
    }

    function getUserID($identifier) {
      require('credentials.php');
      return @parent::query("SELECT USERID FROM $usertable WHERE IDENTIFIER = '$identifier'")->fetch_assoc()['USERID'];
    }

    function getUsername($userid) {
      require('credentials.php');
      return @parent::query("SELECT USERNAME FROM $usertable WHERE USERID = '$userid'")->fetch_assoc()['USERNAME'];
    }

    function deletePost() {
      require('credentials.php');
      @parent::query("DELETE FROM $posttable WHERE POSTID = $_GET[id]");
      header('Location: index');
    }

    function deleteDraft() {
      require('credentials.php');
      @parent::query("DELETE FROM $drafttable WHERE DRAFTID = $_GET[id]");
      header('Location: index');
    }

    function checkRole($userid, $role) {
      require('credentials.php');
      $getRole = @parent::query("SELECT ROLE FROM $usertable WHERE USERID = '$userid'");
      $row = $getRole->fetch_assoc();
      return $row['ROLE'] == $role ? true : false;
    }

    function checkSelf($userid) {
      require('credentials.php');
      return ($userid == self::getCurUser()) ? true : false;
    }

    function updateRole($userid, $role) {
      require('credentials.php');
      $updateRole = @parent::query("UPDATE $usertable SET ROLE = '$role' WHERE USERID = '$userid'");
      return $updateRole;
    }

    function deleteUser($userid) {
      require('credentials.php');
      $deleteUser = @parent::query("DELETE FROM $usertable WHERE USERID = $userid");
      return $deleteUser;
    }

    function createIdentifier($userid) {
      require('credentials.php');
      $identifier = randomString(32);
      $checkExist = @parent::query("SELECT IDENTIFIER FROM $usertable WHERE IDENTIFIER = '$identifier'");
      $valid = true;
      while ($row = $checkExist->fetch_assoc()) {
        if ($identifier == $row['IDENTIFIER']) {
          $valid = false;
        }
      }
      if ($valid) { //identifier doesn't exist yet
        $createIdentifier = @parent::query("UPDATE $usertable SET IDENTIFIER = '$identifier' WHERE USERID = '$userid'");
      } else { // identifier already exists
        self::createIdentifier();
      }
    }

    function getIdentifier($userid) {
      require('credentials.php');
      $getIdentifier = @parent::query("SELECT IDENTIFIER FROM $usertable WHERE USERID = '$userid'");
      $row = $getIdentifier->fetch_assoc();
      if ($row['IDENTIFIER']) {
        return $row['IDENTIFIER'];
      } else { // user doesn't have identifier yet
        self::createIdentifier($userid);
        self::getIdentifier($userid);
      }
    }

    function deleteAuthCookies() {
      foreach ($_COOKIE as $key => $val) {
        if ($key != 'theme' and $key != 'color' and $key != 'color-hacker') {
          setcookie($key, '', 1);
        }
      }
    }

    function auth() {
      require('credentials.php');
      if (isset($_COOKIE['identifier']) and isset($_COOKIE['hashed_password'])) {
        $identifier = $_COOKIE['identifier'];
        $hashed_password = $_COOKIE['hashed_password'];
        $getDBpword = @parent::query("SELECT PASSWORD FROM $usertable WHERE IDENTIFIER = '$identifier'");
        $row = $getDBpword->fetch_assoc();
        $DBpword = $row['PASSWORD'];
        if ($hashed_password == $DBpword) {
          return true;
        }
      }
      return false;
    }

    function unameRequirements($uname) {
      $msg = [];
      if (strlen($uname) == 0 or ctype_space($uname) or $uname == '') {
        array_push($msg, "Username cannot be empty or only whitespace.");
      }
      if (strlen($uname) > 20) {
        array_push($msg, "Username must be at most 20 characters long.");
      }
      if (!preg_match('/^[a-zA-Z0-9_]*$/', $uname)) {
        array_push($msg, "Username can only contain alphanumeric characters (a-z, 0-9, _).");
      }
      $checkExist = @parent::query("SELECT USERNAME FROM $usertable WHERE UPPER(USERNAME) = UPPER('$newUname')");
      if ($checkExist) {
        if ($checkExist->num_rows > 0) {
          array_push($msg, "Username already exists.");
        }
      }
      return $msg;
    }

    function pwordRequirements($pword) {
      $msg = [];
      if (strlen($pword) < 6) {
        array_push($msg, "Password must be at least 6 characters long.");
      }
      if (!(preg_match('/[A-Z]/', $pword) && preg_match('/[a-z]/', $pword))) {
        array_push($msg, "Password must contain at least one lowercase <b>and</b> one uppercase character.");
      }
      if (!(preg_match('/[A-Za-z]/', $pword) && preg_match('/[0-9]/', $pword))) {
        array_push($msg, "Password must contain at least one letter <i>(a-z)</i> <b>and</b> one number <i>(0-9)</i>.");
      }
      return $msg;
    }

    function login() {
      require('credentials.php');
      $msg = [];
      if (isset($_POST["logbtn"])) {
        $uname = $_POST['uname'];
        $pword = $_POST['password'];
        if (!empty($uname) && !empty($pword)) {
          if ($getInfo = @parent::query("SELECT PASSWORD, USERID FROM $usertable WHERE USERNAME='$uname'")) {
            $row = $getInfo->fetch_assoc();
            $dbPword = $row['PASSWORD'];
            if (password_verify($pword, $dbPword)) {
              //pass
              setcookie('identifier', self::getIdentifier($row['USERID']));
              setcookie('hashed_password', $dbPword);
              header("Location: index");
              exit;
            } else {
              //invalid
              array_push($msg, "Invalid password or username");
            }
          } else {
            //query error
            array_push($msg, "Query error");
          }
        } else {
          array_push($msg, "Please enter your username and your password");
        }
      }
      return $msg;
    }

    function register() {
      require('credentials.php');
      $msg = [];
      if (isset($_POST["regbtn"])) {
        $uname = $_POST["username"];
        $pword = $_POST["password"];
        $pwordval = $_POST["passwordval"];

        $pwordmsg = self::pwordRequirements($pword);
        $unamemsg = self::unameRequirements($uname);

        if ($pword != $pwordval) {
          array_push($msg, "Passwords must match");
        }

        //no error|valid = true
        if (empty($unamemsg) && empty($pwordmsg)) {
          $hashed_password = password_hash($pword, PASSWORD_DEFAULT);
          if ($r = @parent::query("INSERT INTO $usertable (USERNAME,PASSWORD) VALUES ('$uname','$hashed_password')")) {
            $userid = @parent::query("SELECT USERID FROM $usertable WHERE USERNAME = '$uname'")->fetch_assoc()['USERID'];
            self::createIdentifier($userid);
            setcookie('identifier', self::getIdentifier($userid));
            setcookie('hashed_password', $hashed_password);
            header('Location: index');
            exit;
          } else {
            array_push($msg, "Query error");
          }
        } else {
          return array_merge($unamemsg, $pwordmsg);
        }
      }
    }
  }

?>
