import { columns } from '@/components/business/columns';
import { BusinessDataTable } from '@/components/business/data-table';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { create, index } from '@/routes/business';
import { BreadcrumbItem, Business } from '@/types';
import { Head, router } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Business',
        href: index().url,
    },
];

export default function BusinessIndex({
    businesses,
}: {
    businesses: Business[];
}) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Business" />
            <div className="mx-auto flex h-full w-1/2 flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between gap-4">
                    <h1 className="text-2xl font-bold">Businesses</h1>

                    <Button
                        type="submit"
                        className="mt-4 h-auto w-2/5 whitespace-normal"
                        onClick={() => router.visit(create())}
                    >
                        Create New Business
                    </Button>
                </div>

                <BusinessDataTable columns={columns} data={businesses} />
            </div>
        </AppLayout>
    );
}
