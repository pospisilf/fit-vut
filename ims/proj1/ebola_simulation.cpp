#include <iostream>
#include <cstdlib>
#include <unistd.h>
#include <fstream>
#include <string>
#include <vector>
#include <sstream>
#include <ctime>

int in_col = 600; //sloupce
int in_row = 600; //řádky
float const_d;    //pravděpodobnost že se buňky neuzdravily a pokračuje nekróza
float const_l;
float const_z;             // z + d + l = 1
float const_c;             //počáteční kontaminace
float const_t = 1.0 / 3.0; //nastání terminálního stádia
int times = 100;
int umrtiOrganismu = 36; //pocet dni, po kterych behem nakazeni organismus umira

//deklarace fci
int runSimulation();

//bunka
typedef enum
{
    cStat0 = 0, //zdravá buňka
    cStat1 = 1, //nakažená buňka
    cStat2 = 2, //buňka s opožděnou imunitní reakcí
    cStat3 = 3  //mrtvá buňka
} Cell;

class CellularAutomata
{
public:
    int col, row = 0;
    float const_d, const_l, const_z, const_t = 0;
    int zdraveBunky, mrtveBunky, nakazeneBunky = 0;
    void dalsiFaze(bool regen, bool prodleva);
    void nahodneRozdeleni();
    void pocatecniNakazeni(int nakazeneBunkyPocatecni);
    Cell stavBunky();
    int denKonecnehoStavu() const;
    CellularAutomata(int row, int col, float const_l, float const_d, float const_t);
    ~CellularAutomata();
    std::vector<Cell **> pocitacDni;
    Cell **generace = NULL;
    int pocetDni = 0;
    Cell dalsiStadium(int in_row, int in_col, bool regen, bool prodleva);
    int nakazeniSousede(int in_row, int in_col);
    Cell **alokaceSite();
};

int celkovaumrtnost = 0;

int main(int argc, char **argv)
{
    std::cout << "IMS projekt: Epidemiologické modely pomocí celulárních automatů" << std::endl
              << std::endl;
    std::cout << "Chcete simulaci spustit v vlastními hodnotami? (a/n) ";
    std::string ownValues;
    std::cin >> ownValues;

    if (ownValues == "a")
    {
        std::cout << "Zadejte vlastní hodnoty:" << std::endl;
        std::cout << "Počet řádků buněk (1-1500): ";
        int user_row;
        std::cin >> user_row;
        if (user_row > 0 && user_row <= 1500)
        {
            in_row = user_row;
        }

        std::cout << "Počet sloupců buněk (1-1500): ";
        int user_col;
        std::cin >> user_col;
        if (user_col > 0 && user_col <= 1500)
        {
            in_col = user_col;
        }

        std::cout << "Pravděpodobnost neobnovení mrtvé buňky a následné nekrózy (d) (0.0-1.0): ";
        float user_d;
        std::cin >> user_d;
        if (user_d >= 0.0 && user_d <= 1)
        {
            const_d = user_d;
        }
        else
        {
            float random_d = (30 + (rand() % 10));
            const_d = random_d / 100;
        }

        std::cout << "Pravděpodobnost opoždění imunitní reakce (l): ";
        float user_l;
        std::cin >> user_l;
        if (user_l >= 0.0 && user_l <= 1)
        {

            const_l = user_l;
        }
        else
        {
            float random_l = (30 + (rand() % 10));
            const_l = random_l / 1000;
        }

        //kontorla + tisk z
        if ((const_l + const_d) > 1)
        {
            float random_l = (30 + (rand() % 10));
            const_l = random_l / 1000;

            float random_d = (30 + (rand() % 10));
            const_d = random_d / 100;
        }

        const_z = 1 - const_d - const_l;
        std::cout << "Pravděpdoobnost správnosti imunitní reakce DOPOČÍTÁNO (z): " << const_z << std::endl;

        std::cout << "Počáteční napadení buněk (>0 && <= 1)  (c): ";
        float user_c;
        std::cin >> user_c;
        if (user_c > 0.0 && user_c <= 1)
        {
            const_c = user_c;
        }
        else
        {
            float random_c = (50 + (rand() % 10));
            const_c = random_c / 10000;
        }
        std::cout << "Terminální podmínka (>0 && <= 1) (t): "; //počáteční zamoření (random od 0.005-0.006)
        float user_t;
        std::cin >> user_t;
        if (user_t > 0.0 && user_t <= 1)
        {
            const_t = user_t;
        }

        std::cout << "Počet osob v simulaci (1-1000) (per): "; //počáteční zamoření (random od 0.005-0.006)
        int user_per;
        std::cin >> user_per;
        if (user_per > 0 && user_per <= 1000)
        {
            times = user_per;
        }

        runSimulation();
    }else
    {
        std::cout << "Spouštím s předdefinovanými hodnotami." << std::endl
                  << std::endl;

        srand((unsigned)time(0));

        float random_c = (50 + (rand() % 10));
        const_c = random_c / 10000;

        float random_l = (30 + (rand() % 10));
        const_l = random_l / 1000;

        float random_d = (30 + (rand() % 10));
        const_d = random_d / 100;

        const_z = 1 - const_d - const_l;
        std::cout << "Předdefinované hodnoty jsou následující:" << std::endl;
        std::cout << "Rozměry pole buněk                                (in_col x in_row): " << in_col << " x " << in_row << std::endl;
        std::cout << "Pravděpodobnost neobnovení mrtvé buňky a následné nekrózy (d): " << const_d << std::endl; //neobnovení buněk -> šíření nekrózy
        std::cout << "Pravděpodobnost opoždění imunitní reakce                  (l): " << const_l << std::endl; //pravděpodobnost opožděné imunitní reakce
        std::cout << "Pravděpdoobnost správnosti imunitní reakce                (z): " << const_z << std::endl; //správnost imunitní reakce
        std::cout << "Počáteční napadení buněk                                  (c): " << const_c << std::endl; //počáteční zamoření (random od 0.005-0.006)
        std::cout << "Terminální podmínka                                       (t): " << const_t << std::endl; //počáteční zamoření (random od 0.005-0.006)
        std::cout << "Počet osob v simulaci                                   (per): " << times << std::endl;   //počáteční zamoření (random od 0.005-0.006)
        std::cout << "Stiskněte enter pro zahájení simulace." << std::endl;
        std::cin.ignore();
        std::cin.get();
        runSimulation();
    }
}

//hlavni funkce
int runSimulation()
{
    //pocitadla
    int deadCount = 0;
    int deadDays = 0;
    int survCount = 0;
    int survDays = 0;

    for (int i = 0; i < times; i++)
    {
        //inicializace automatu
        CellularAutomata CellularAutomata(in_row, in_col, const_l, const_d, const_t);
        int nakazeneBunky = in_row * in_col * const_c;
        CellularAutomata.pocatecniNakazeni(nakazeneBunky);
        std::cout << "Probiha simulace c. " << i + 1 << " z " << times << std::endl;

        while (CellularAutomata.stavBunky() == cStat1)
        {
            CellularAutomata.nahodneRozdeleni();
        }
        if (CellularAutomata.stavBunky() == cStat3)
        {
            deadCount++;
            deadDays = deadDays + CellularAutomata.denKonecnehoStavu();
        }
        else
        {
            survCount++;
            survDays = survDays + CellularAutomata.denKonecnehoStavu();
        }
    }

    std::cout << std::endl
              << "Zemrelo: " << deadCount << std::endl;
    std::cout << "Prumerny den smrti: " << deadDays / deadCount << std::endl;
    std::cout << "Prezilo: " << survCount << std::endl;
    std::cout << "Prumerny den vyleceni: " << survDays / survCount << std::endl;
    return 0;
}

Cell **CellularAutomata::alokaceSite()
{
    Cell **bunka = new Cell *[row];
    for (int i = 0; i < row; ++i)
        bunka[i] = new Cell[col];
    return bunka;
}

CellularAutomata::~CellularAutomata()
{
    for (auto i : pocitacDni)
    {
        for (int j = 0; j < row; ++j)
            delete[] i[j];
        delete[] i;
    }
}

CellularAutomata::CellularAutomata(int row, int col, float const_l, float const_d, float const_t) : col(col), row(row), const_l(const_l), const_d(const_d), const_t(const_t)
{
    generace = alokaceSite();
    for (int i = 0; i < row; ++i)
        for (int j = 0; j < col; ++j)
            generace[i][j] = cStat0;
    pocitacDni.push_back(generace);
}

int CellularAutomata::nakazeniSousede(int in_row, int in_col)
{
    int pocetNakazenych = 0;
    for (int i = in_row - 1; i <= in_row + 1; ++i)
    {
        int x = 0;
        if (i < 0)
        {
            x = row + i;
        }
        else
        {
            x = i % row;
        }
        for (int c = in_col - 1; c <= in_col + 1; ++c)
        {
            int y = 0;
            if (c < 0)
            {
                y = col + c;
            }
            else
            {
                y = c % col;
            }
            Cell value = generace[x][y];
            if (value == cStat1 || value == cStat2)
            {
                pocetNakazenych++;
            }
        }
    }
    return pocetNakazenych;
}

Cell CellularAutomata::dalsiStadium(int in_row, int in_col, bool regen, bool prodleva)
{
    Cell value = generace[in_row][in_col];
    if (value == cStat0)
    {
        zdraveBunky++;
        value = nakazeniSousede(in_row, in_col) > 0 ? cStat1 : cStat0;
    }
    else if (value == cStat1)
    {
        nakazeneBunky++;
        value = prodleva ? cStat2 : cStat3;
    }
    else if (value == cStat2)
    {
        nakazeneBunky++;
        value = cStat3;
    }
    else
    {
        mrtveBunky++;
        value = regen ? cStat0 : cStat3;
    }
    return value;
}

void CellularAutomata::dalsiFaze(bool regen, bool prodleva)
{
    pocetDni++;
    Cell **bunka = alokaceSite();
    mrtveBunky, zdraveBunky, nakazeneBunky = 0;
    for (int in_row = 0; in_row < row; ++in_row)
    {
        for (int in_col = 0; in_col < col; ++in_col)
        {
            bunka[in_row][in_col] = dalsiStadium(in_row, in_col, regen, prodleva);
        }
    }
    pocitacDni.push_back(bunka);
    generace = bunka;
}

void CellularAutomata::nahodneRozdeleni()
{
    int choice = rand() % 100;
    if (choice < const_l * 100)
        dalsiFaze(true, true);
    else if (choice < (const_l + const_d) * 100)
        dalsiFaze(false, false);
    else
        dalsiFaze(true, false);
}

void CellularAutomata::pocatecniNakazeni(int nakazeneBunkyPocatecni)
{
    for (int i = 0; i < nakazeneBunkyPocatecni; ++i)
    {
        size_t x = rand() % row;
        size_t y = rand() % col; //vybere náhodný řádek a sloupec
        generace[x][y] = cStat1; //buňka se posune do stádia 1
    }
}

Cell CellularAutomata::stavBunky()
{
    if (pocetDni > umrtiOrganismu)
    {
        return cStat3;
    }
    else
    {
        float pocetNakazenych = 0;
        float nazivu = 0;
        for (int x = 0; x < row; ++x)
        {
            for (int y = 0; y < col; ++y)
            {
                Cell c = generace[x][y];
                if (c == cStat1 || c == cStat2)
                {
                    pocetNakazenych++;
                }
                else if (c == cStat0)
                {
                    nazivu++;
                }
            }
        }
        if (nazivu < row * col * const_t)
        {
            return cStat3;
        }
        if (pocetNakazenych > 0)
        {
            return cStat1;
        }
        else
        {
            return cStat0;
        }
    }
}

int CellularAutomata::denKonecnehoStavu() const
{
    return pocetDni;
}
