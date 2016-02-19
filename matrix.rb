require 'stringio'
require_relative 'cell'

class Matrix
  attr_reader :x_max, :y_max

  def initialize fields
    @matrix = create fields
    @x_max = @matrix.first.length
    @y_max = @matrix.length
  end

  def exists? x, y
    0 <= x && x < @x_max && 0 <= y && y < @y_max
  end

  def at x, y
    raise "invalid position, x: #{x}, y: #{y}" unless exists?(x, y)
    @matrix[y][x]
  end

  def inspect
    buf = StringIO.new
    @matrix.each do |row|
      row.each do |col|
        buf << "#{col.char}#{col.marked? ? "*" : " "} "
      end
      buf << "\n"
    end
    buf.string
  end

  private
  def create fields
    matrix = []
    fields.each do |row|
      cols = row.map do |col|
        Cell.new col
      end
      matrix << cols
    end
    matrix
  end
end

if __FILE__ == $0
  matrix = Matrix.new [ [ "a", "b" ], [ "c", "d" ] ]
  puts "0x0:\t#{matrix.exists?(0, 0)}==true"
  puts "0x1:\t#{matrix.exists?(0, 1)}==true"
  puts "1x0:\t#{matrix.exists?(1, 0)}==true"
  puts "1x1:\t#{matrix.exists?(1, 1)}==true"
  puts "-1x0:\t#{matrix.exists?(-1, 0)}==false"
  puts "1x2:\t#{matrix.exists?(1, 2)}==false"
  puts "0x0.char:\t#{matrix.at(0, 0).char}==a"
  puts "0x0.marked?:\t#{matrix.at(0, 0).marked?}==false"
  puts "0x0.mark!:\t#{matrix.at(0, 0).mark!}==true"
  puts "0x0.marked?:\t#{matrix.at(0, 0).marked?}==true"
  puts "0x0.unmark!:\t#{matrix.at(0, 0).unmark!}==false"
  puts "0x0.marked?:\t#{matrix.at(0, 0).marked?}==false"
  puts "1x1.char:\t#{matrix.at(1, 1).char}==d"
end
