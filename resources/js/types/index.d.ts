import { InertiaLinkProps } from '@inertiajs/react';
import { LucideIcon } from 'lucide-react';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
    flash: FlashMessage;
    [key: string]: unknown;
}

export interface FlashMessage {
    success?: string;
    error?: string;
    warning?: string;
    info?: string;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    two_factor_enabled?: boolean;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface Country {
    id: number;
    name: string;
    code: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface Currency {
    id: number;
    name: string;
    code: string;
    symbol: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface Business {
    id: number;
    name: string;
    code: string;
    currency_id: number;
    country_id: number;
    [key: string]: unknown; // This allows for additional properties...
}

export interface Account {
    business_id: number;
    id: number;
    code: string;
    name: string;
    type: string;
    parent_id: number | null;
    is_selectable: boolean;
    is_system: boolean;
    children: Account[];
    [key: string]: unknown; // This allows for additional properties...
}
    