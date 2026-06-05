#include <stdio.h>

int main() {
    int rows;

    printf("Enter number of rows: ");
    scanf("%d", &rows);

    for (int i = 1; i <= rows; i++) {
        for (int j = i; j < rows; j++) {
            printf("  ");
        }

        for (int j = i; j < 2*i; j++) {
            printf("%d ", j);
        }

        for (int j = 2*i - 2; j >= i; j--) {
            printf("%d ", j);
        }
        printf("\n");
    }
    return 0;
}