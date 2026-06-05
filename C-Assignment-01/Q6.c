#include <stdio.h>

int main() {
    int rows;

    printf("Enter number of rows: ");
    scanf("%d", &rows);

    for (int i = 1; i <= rows; i++) {
        for (int j = i; j < rows; j++) {
            printf("  ");
        }
        for (int k = 1; k <= (2*i - 1); k++) {
            printf("* ");
        }
        printf("\n");
    }
    return 0;
}
