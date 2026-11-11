<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class MenuHelper
{
  /**
   * Menu Principal (Principalement pour le rôle 'user' connecté)
   */
  public static function getMainNavItems()
  {
    return [
      [
        "icon" => "dashboard",
        "name" => "Dashboard",
        "roles" => ["client"],
        "path" => "/dashboard",
        "route_name" => "dashboard",
      ],

      [
        "icon" => "chat",
        "name" => "Ask AI",
        "roles" => ["client"],
        "path" => "/chat",
        "route_name" => "chat",
      ],
      [
        "icon" => "book",
        "name" => "My documents",
        "roles" => ["client"],
        "path" => "/documents",
        "route_name" => "documents",
      ],
      [
        "icon" => "book",
        "name" => "Library",
        "roles" => ["client"],
        "path" => "/library",
        "route_name" => "library.index",
      ],
      [
        "icon" => "hystory",
        "name" => "My hystory chat",
        "roles" => ["client"],
        "path" => "/hystory",
        "route_name" => "hystory",
      ],
      [
        "icon" => "reports",
        "name" => "My reports ",
        "roles" => ["client"],
        "path" => "/reports",
        "route_name" => "reports",
      ],

      /* [
        "icon" => "movie",
        "name" => "Library",
        "roles" => ["client"],
        "path" => "/videos",
        "route_name" => "videos",
      ], */
      // [
      //   "icon" => "profile",
      //   "name" => "Tax profile",
      //   "roles" => ["client"],
      //   "path" => "/tax-profile",
      //   "route_name" => "tax-profile",
      // ],
      [
        "icon" => "subscription",
        "name" => "Subscription",
        "roles" => ["client"],
        "path" => "/subscription",
        "route_name" => "subscription.index",
      ],
      [
        "icon" => "user-profile",
        "name" => "My profile",
        "roles" => ["client"],
        "path" => "/profile",
        "route_name" => "profile.edit",
      ],
    ];
  }

  /**
   * Menu d'Administration (Strictement pour le rôle 'admin')
   */
  public static function getAdminNavItems()
  {
    return [
      [
        "icon" => "dashboard",
        "name" => "Admin Dashboard",
        "roles" => ["admin"],
        "path" => "/admin/dashboard",
        "route_name" => "admin.dashboard",
      ],
      [
        "icon" => "user-profile",
        "name" => "clients",
        "roles" => ["admin"],
        "path" => "/admin/users",
        "route_name" => "users.index",
      ],
      [
        "icon" => "pages",
        "name" => "Content Management",
        "roles" => ["admin"],
        "path" => "/admin/content",
        "route_name" => "content.index",
        "subItems" => [
          [
            "name" => "Contents",
            "path" => "/admin/content",
            "route_name" => "content.index",
          ],
          [
            "name" => "Add Content",
            "path" => "/admin/content/create",
            "route_name" => "content.create",
          ],
          [
            "name" => "Add Ai Default messages",
            "path" => "/ai/defaultmessages",
            "route_name" => "default.messages",
          ]
        ],
      ],
      [
        "icon" => "subscription",
        "name" => "Tokens",
        "roles" => ["admin"],
        "path" => "/admin/tokens",
        "route_name" => "admin.tokens",
      ],
      [
        "icon" => "subscription",
        "name" => "Plans",
        "roles" => ["admin"],
        "path" => "/admin/plans",
        "route_name" => "admin.plans",
      ],
      [
        "icon" => "user-profile",
        "name" => "Manage Profile",
        "roles" => ["admin"],
        "path" => "/profile",
        "route_name" => "profile.edit",
      ],  
      [
      "icon" => "pages",
        "name" => "AI Configuration",
        "roles" => ["admin"],
        "path" => "/ai/providers",
        "route_name" => "ai.providers",
        "subItems" => [
          [
        "name" => "Ai provider config",
        "path" => "/admin/ai/providers",
        "route_name" => "ai.providers"
      ],
      [
        "name" => "Ai model configuration",
        "path" => "/admin/ai/models",
        "route_name" => "ai.models"
      ],
      [
        "name" => "Ai defaut model config",
        "path" => "/admin/ai",
        "route_name" => "ai.configuration"
      ]
      ]
    ]
      ];
  }

  /**
   * Regroupement global et filtrage automatique par rôle
   */
  public static function getMenuGroups()
  {
    $role = strtolower(Auth::user()?->role->value ?? "");

    // Si l'utilisateur est admin, on lui affiche le menu Admin, sinon le menu User
    if ($role === "admin") {
      $groups = [
        [
          "title" => "Administration",
          "items" => self::getAdminNavItems(),
        ],
      ];
    } else {
      $groups = [
        [
          "title" => "Menu Principal",
          "items" => self::getMainNavItems(),
        ],
      ];
    }

    // Sécurité additionnelle : Filtrage basé sur le tableau 'roles'
    foreach ($groups as &$group) {
      $group["items"] = array_values(
        array_filter($group["items"], function ($item) use ($role) {
          return isset($item["roles"]) && in_array($role, $item["roles"]);
        })
      );
    }

    return array_values(
      array_filter($groups, fn($group) => !empty($group["items"]))
    );
  }

  /**
   * Vérification de la route active pour Blade et Alpine.js
   */
  public static function isActive($routeName)
  {
    return Route::currentRouteName() === $routeName;
  }

  /**
   * Générateur de SVG d'icônes
   */
  public static function getIconSvg($iconName)
  {
    $icons = [
      "dashboard" =>
        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"></path></svg>',
      "profile" =>
        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M6 20C6 16.6863 9.31371 14 13.5 14C17.6863 14 21 16.6863 21 20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>',
      "book" =>
        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 6.00002L4 3.00002V17L12 20M12 6.00002L20 3.00002V17L12 20M12 6.00002V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      "chat" =>
        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 12H8.01M12 12H12.01M16 12H16.01M21 12C21 16.4183 16.9706 20 12 20C10.5177 20 9.12301 19.6355 7.91502 18.995L3 20L4.31298 16.161C3.17408 14.9922 2.5 13.5684 2.5 12C2.5 7.58172 6.52944 4 12 4C17.4706 4 21 7.58172 21 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>',
      "movie" =>
        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 10L19.5528 7.72361C20.2177 7.39116 21 7.87465 21 8.61803V15.382C21 16.1254 20.2177 16.6088 19.5528 16.2764L15 14M5 18H13C14.1046 18 15 17.1046 15 16V8C15 6.89543 14.1046 6 13 6H5C3.89543 6 3 6.89543 3 8V16C3 17.1046 3.89543 18 5 18Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>',
      "user-profile" =>
        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M12 14C7.58172 14 4 17.5817 4 22H20C20 17.5817 16.4183 14 12 14Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>',
      "pages" =>
        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.50391 4.25C8.50391 3.83579 8.83969 3.5 9.25391 3.5H15.2777C15.4766 3.5 15.6674 3.57902 15.8081 3.71967L18.2807 6.19234C18.4214 6.333 18.5004 6.52376 18.5004 6.72268V16.75C18.5004 17.1642 18.1646 17.5 17.7504 17.5H16.248V17.4993H14.748V17.5H9.25391C8.83969 17.5 8.50391 17.1642 8.50391 16.75V4.25ZM14.748 19H9.25391C8.01126 19 7.00391 17.9926 7.00391 16.75V6.49854H6.24805C5.83383 6.49854 5.49805 6.83432 5.49805 7.24854V19.75C5.49805 20.1642 5.83383 20.5 6.24805 20.5H13.998C14.4123 20.5 14.748 20.1642 14.748 19.75L14.748 19ZM7.00391 4.99854V4.25C7.00391 3.00736 8.01127 2 9.25391 2H15.2777C15.8745 2 16.4468 2.23705 16.8687 2.659L19.3414 5.13168C19.7634 5.55364 20.0004 6.12594 20.0004 6.72268V16.75C20.0004 17.9926 18.9931 19 17.7504 19H16.248L16.248 19.75C16.248 20.9926 15.2407 22 13.998 22H6.24805C5.00541 22 3.99805 20.9926 3.99805 19.75V7.24854C3.99805 6.00589 5.00541 4.99854 6.24805 4.99854H7.00391Z" fill="currentColor"></path></svg>',
      "hystory" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-gray-500 hover:text-blue-600 transition-colors">
    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
    <path d="M3 3v5h5" />
    <path d="M12 7v5l4 2" />
</svg>
',
      "reports" => '<svg
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
    stroke="currentColor"
    stroke-width="1.8"
    class="w-6 h-6"
>
    <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M7 3h7l5 5v13a1 1 0 01-1 1H7a2 2 0 01-2-2V5a2 2 0 012-2z"
    />

    <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M14 3v5h5"
    />

    <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M9 14h6M9 18h3"
    />

    <circle cx="17" cy="16" r="3" />

    <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M19 18l2 2"
    />
</svg>',
      "subscription" => '<svg 
    xmlns="http://www.w3.org/2000/svg" 
    viewBox="0 0 24 24" 
    fill="none" 
    stroke="currentColor" 
    stroke-width="1.5" 
    stroke-linecap="round" 
    stroke-linejoin="round" 
    class="w-6 h-6 text-emerald-600 dark:text-emerald-400"
    aria-hidden="true"
>
    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
    <path d="m9 11 2 2 4-4" />
</svg>
',
    ];

    return $icons[$iconName] ??
      '<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/></svg>';
  }
}
